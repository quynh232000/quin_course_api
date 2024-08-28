<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use App\Models\Transaction;
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
}
