<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Response;
use App\Models\Setting;
use App\Models\Voucher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Str;
use Hash;

class OrderController extends Controller
{


    /**
     * @OA\Post(
     *      path="/api/order/create",
     *      operationId="create_order",
     *      tags={"Order"},
     *      summary="create a new order",
     *      description="Create a new order",
     *      @OA\Parameter(
     *          name="payment_method",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          description="Payment method: banking, vnpay"
     *      ),
     *       @OA\Parameter(
     *          name="voucher_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          description="Voucher ID to apply to order"
     *      ),
     *      security={{
     *          "bearer": {}
     *      }},
     *      @OA\Response(
     *          response=400,
     *          description="Invalid ID supplied"
     *      ),
     * )
     */
    public function create_order(Request $request)
    {
        try {
            if (!$request->payment_method || $request->payment_method == '') {
                return Response::json(false, 'Payment method is required');
            }
            $checkOrder = Order::where('user_id', auth('api')->id())->where('status', 'new')->first();
            if ($checkOrder) {
                $checkOrder->delete();
            }
            // check cart user
            $carts = Cart::where('user_id', auth('api')->id())->get();
            if (count($carts) == 0) {
                return Response::json(false, 'Cart is empty');
            }
            $subtotal = 0;
            foreach ($carts as $cart) {
                $subtotal += $cart->course->price;
            }
            // check voucher
            $hasVoucher = false;
            $priceVoucher = 0;
            if ($request->voucher_id && $request->voucher_id != '') {
                $voucher = Voucher::find($request->voucher_id);
                if (!$voucher || $voucher->status() != 'active') {
                    return Response::json(false, 'Voucher is invalid or inactive');
                }
                if ($subtotal < $voucher->min_price) {
                    return Response::json(false, 'Voucher min price is higher than subtotal');
                }
                $hasVoucher = true;
                $priceVoucher = $voucher->discount_amount * $subtotal / 100;
            }
            // create order
            $order_code = strtoupper(auth('api')->user()->username . '-' . Str::random(4));
            $hash = Str::random(32);
            $order = Order::create([
                'user_id' => auth('api')->id(),
                'email' => auth('api')->user()->email,
                'subtotal' => $subtotal,
                'total' => ($subtotal - $priceVoucher),
                'status' => 'new',
                'voucher_id' => $hasVoucher ? $request->voucher_id : null,
                'payment_method' => $request->payment_method,
                'order_code' => $order_code,
                'hash' => $hash
            ]);
            // get infor payment admin card
            $payment_info['momo'] = Setting::where('type', 'BANK')->get();
            $payment_info['bank'] = Setting::where('type', 'BANKING')->get();
            // add order detail 
            foreach ($carts as $cart) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'course_id' => $cart->course_id,
                    'price' => $cart->course->price
                ]);
            }
            return Response::json(true, 'Create order successfully', ['order' => $order, 'payment_info' => $payment_info]);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *      path="/api/order/confirmpayment/{order_id}/{order_code}",
     *      operationId="confirmpayment",
     *      tags={"Order"},
     *      summary="confirmpayment",
     *      description="confirmpayment",
     *      @OA\Parameter(
     *          name="order_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="order_code",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      security={{
     *          "bearer": {}
     *      }},
     *      @OA\Response(
     *          response=400,
     *          description="Invalid ID supplied"
     *      ),
     * )
     */
    public function confirmpayment($order_id, $order_code)
    {
        try {
            if (!$order_id || !$order_code) {
                return Response::json(false, 'Order ID or Order code is missing');
            }
            $order = Order::find($order_id);
            if (!$order || $order->order_code != $order_code) {
                return Response::json(false, 'Order not found');
            }
            if ($order->status != 'new') {
                return Response::json(false, 'Order is already paid');
            }

            // send mail to admin 
            $emailAdmin = Setting::where(['type' => 'ADMIN', 'key' => 'MAIL'])->pluck('value')->first();
           
            $data['APP_URL']='http://localhost:8000';
            $data['email'] = $emailAdmin;
            $data['title'] = "Confirm payment for new order";
            $data['order'] = $order;
            $data['token_success'] = Hash::make($order->hash . 'success');
            $data['token_error'] = Hash::make($order->hash . 'error');

            Mail::send("email.confirmpayment", ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            // save change status order
            $order->status = 'pending';
            $order->save();
            // remove cart user 
            Cart::where('user_id', auth('api')->id())->delete();
            return Response::json(true, 'Confirm payment successfully');
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
    
}
