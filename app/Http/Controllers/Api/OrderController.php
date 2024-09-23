<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Response;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Voucher;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Str;
use Hash;
use Illuminate\Support\Facades\Http;

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
     *      operationId="confirmpayment1",
     *      tags={"Order"},
     *      summary="Check your status or change",
     *      description="Check your status or change",
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
                return Response::json(false, 'Order is already paid', $order);
            }

            // send mail to admin 
            $emailAdmin = Setting::where(['type' => 'ADMIN', 'key' => 'MAIL'])->pluck('value')->first();

            $data['APP_URL'] = env('APP_URL', 'http://localhost:8000');
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
            return Response::json(true, 'Confirm payment successfully', $order);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *      path="/api/order/checkpayment/{order_id}/{order_code}",
     *      operationId="checkpayment",
     *      tags={"Order"},
     *      summary="checkpayment",
     *      description="checkpayment",
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

    public function checkpayment($order_id, $order_code)
    {
        try {

            if (!$order_id || !$order_code) {
                return Response::json(false, 'Missing Order ID or Order Code');
            }
            $order = Order::find($order_id);
            if (!$order || $order->order_code != $order_code) {
                return Response::json(false, 'Order not found');
            }
            if ($order->status == 'pending') {
                return Response::json(true, 'Order is pending', $order->status);
            }
            return Response::json(false, 'Order is paid', $order->status);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }



    /**
   * @OA\Post(
   *      path="/api/order/{order_code}/check_amount",
   *      operationId="check_amount",
   *      tags={"Order"},
   *      summary="check_amount",
   *      description="check_amount",

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

    public function check_amount($order_code)
    {
        set_time_limit(120);
        try {

            if (!$order_code) {
                return Response::json(false, 'Missing  Order Code');
            }
            $order = Order::where('order_code', $order_code)->first();
            if (!$order) {
                return Response::json(false, 'Order not found');
            }
            if($order->status !='new'){
                return Response::json(false, 'Order is completed', $order);
            }
            $attempts = 0;
            $flag = true;
            $responseJson = Response::json(false, 'Order not pay now', ['is_changed' => false, 'order' => $order, 'transaction' => null]);

            while ($flag && $attempts < 1) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('API_SEPAY_KEY', 'EMYGNDWWJOSONPF7587UBMVXENPBBGXTVUUACY092VZJOFEYFYPKZK8PD26HW45A'),
                    'Accept' => 'application/json',
                ])->get('https://my.sepay.vn/userapi/transactions/list');
                if ($response->successful()) {
                    $data = $response->json();
                    $transactions = $data['transactions'];
                    foreach ($transactions as $key => $item) {
                        if (Str::contains(Str::lower($item['transaction_content']), Str::lower($order->order_code))) {

                            $transaction = BankTransaction::create([
                                'bank_id' => $item['id'],
                                'bank_brand_name' => $item['bank_brand_name'],
                                'account_number' => $item['account_number'],
                                'transaction_date' => $item['transaction_date'],
                                'amount_out' => $item['amount_out'],
                                'amount_in' => $item['amount_in'],
                                'accumulated' => $item['accumulated'],
                                'transaction_content' => $item['transaction_content'],
                                'reference_number' => $item['reference_number']
                            ]);
                            if ($order->total <= $item['amount_in']) {
                                $order->status = 'completed';
                                $order->save();
                                // update enrollment 
                                $order_details = OrderDetail::where('order_id', $order->id)->pluck('course_id')->all();
                                foreach ($order_details as $course_id) {
                                    $courseUpdate = Course::find($course_id);
                                    $courseUpdate->enrollment_count += 1;
                                    $courseUpdate->save();
                                    Enrollment::create([
                                        'user_id' => $order->user_id,
                                        'course_id' => $course_id,
                                        'status' => true,
                                        'start_date' => Carbon::now()
                                    ]);
                                }
                                // create transaction
                                $bank_number = Setting::where(['type' => 'ADMIN', 'key' => 'BANKING_NUMBER'])->pluck('value');
                                Transaction::create([
                                    'order_id' => $order->id,
                                    'from_name' => $order->email,
                                    'from_number_card' => '',
                                    'type' => 'banking',
                                    'to_user' => 'admin',
                                    'to_number_card' => $bank_number,
                                    'amount' => $order->total,
                                    'status' => 'success'
                                ]);
                                // update voucher 
                                if ($order->voucher_id) {
                                    $voucher = Voucher::find($order->voucher_id);
                                    $voucher->used += 1;
                                    $voucher->save();
                                }

                                $responseJson = Response::json(true, 'Order is paid', ['is_changed' => true, 'order' => $order, 'transaction' => $transaction]);

                                Cart::where('user_id', auth('api')->id())->delete();
                            } else {
                               
                                $responseJson = Response::json(false, 'Payment not completed. Your amount is not enough!', ['is_changed' => false, 'order' => $order, 'transaction' => $transaction]);
                            }
                            $flag = false;
                            break;
                        }
                    }
                }
                $attempts++;
                // if ($flag == true) {
                //     sleep(10);
                // }
            }
           
            return $responseJson;
            // return $attempts;
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/api/order/my_order",
     *      operationId="myorder",
     *      tags={"Order"},
     *      summary="myorder",
     *      description="myorder",
     *      security={{
     *          "bearer": {}
     *      }},
     *      @OA\Response(
     *          response=400,
     *          description="Invalid ID supplied"
     *      ),
     * )
     */
    public function my_order()
    {
        try {
            $data = Order::where('user_id', auth('api')->id())->with('orderDetails.course')->limit(20)->get();
            return Response::json(true, 'Get my order successfully', $data);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
    public function real_check_payment($order_code)
    {

        try {
            if (!$order_code) {
                return Response::json(false, 'Missing Order Code ');
            }
            // check order is exist
            $order = Order::where('order_code', $order_code)->first();
            if (!$order) {
                return Response::json(false, 'Order not found');
            }
            if ($order->status != 'new') {
                return Response::json(false, 'Transaction for this order had already been completed');
            }
            // check bank transaction history
            $check_code = BankTransaction::where('transaction_content', 'like', '%' . $order_code . '%')->first();
            if (!$check_code) {
                return Response::json(false, 'No transaction for order code: ' . $order_code);
            }
            // check amount
            if ($check_code->amount_in != $order->total) {
                return Response::json(false, 'Amount not match with transaction: ' . $order_code);
            }
            // success

            return Response::json(true, 'success', $order->status);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
}
