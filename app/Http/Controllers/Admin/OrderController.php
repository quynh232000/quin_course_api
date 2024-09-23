<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function admin_confirm_order($order_id, Request $request)
    {
        try {
            $token = $request->token ?? null;
            if (!$token || !$order_id) {
                return redirect()->route('admin.error')->with('message', 'Url is not valid');
            }
            $order = Order::find($order_id);
            if (!$order) {
                return redirect()->route('admin.notfund')->with('message', 'Order not found');
            }
            if (Hash::check($order->hash . 'success', $token)) {
                // update status order
                $order->status = 'completed';
                $order->save();
                // update enrollment 
                $order_details = OrderDetail::where('order_id', $order->id)->pluck('course_id')->all();
                foreach ($order_details as $course_id) {
                    Enrollment::create([
                        'user_id' => $order->user_id,
                        'course_id' => $course_id,
                        'status' => true,
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

            } else if (Hash::check($order->hash . 'error', $token)) {
                // update status order
                $order->status = 'failed';
                $order->save();
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
                    'status' => 'failed',
                ]);
            } else {
                return redirect()->route('admin.notfund')->with('message', 'Invalid token.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.notfund')->with('message', $e->getMessage());
        }
    }
    public function manage_orders()
    {
        $data = Order::limit(20)->paginate(20);
        return view('pages.order.listorder', compact('data'));
    }
    public function order_detail($order_code)
    {
        if (!$order_code) {
            return redirect()->back()->with('error', 'Order Code is required');
        }
        $data = Order::where('order_code', $order_code)->with(['user', 'orderDetails.course.user', 'voucher'])->first();
        if (!$data) {
            return redirect()->back()->with('error', 'Order not found');
        }
        return view('pages.order.order_detail', compact('data'));
    }
    public function cancel_order($order_id)
    {
        if (!$order_id) {
            return redirect()->back()->with('error', 'Order ID is required');
        }
        $order = Order::find($order_id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }
        $order->status = 'failed';
        $order->save();
        return redirect()->back()->with('success', 'Order canceled successfully');
    }
    public function confirm_order($order_id)
    {
        if (!$order_id) {
            return redirect()->back()->with('error', 'Order ID is required');
        }
        $order = Order::find($order_id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }
        // update order completed
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
                'start_date'=>Carbon::now()
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
        return redirect()->back()->with('success', 'Order completed successfully');
    }
}
