<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;
class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;
        if ($request->type && $request->type != '') {
            switch ($request->type) {
                case 'happening':
                    $data = Voucher::where('date_start', '<=', now())
                        ->where('date_end', '>=', now())
                        ->paginate($limit);
                    break;
                case 'happened':
                    $data = Voucher::where('date_end', '<', now())
                        ->paginate($limit);
                    break;
                case 'comming':
                    $data = Voucher::where('date_end', '>', now())
                        ->paginate($limit);
                    break;

                default:
                    $data = Voucher::limit($limit)->paginate($limit);
                    break;
            }
        } else {
            $data = Voucher::limit($limit)->paginate($limit);
        }
        return view('pages.voucher.listvouchers', compact('data'));

    }
    public function create()
    {
        return view('pages.voucher.create_voucher');
    }
    public function update($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Voucher Id is required');
        }
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return redirect()->back()->with('error', 'Voucher not found');
        }
        return view('pages.voucher.create_voucher', compact('voucher'));
    }

    public function _create(Request $request, $id = null)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'min_price' => 'required',
            'discount_amount' => 'required',
            'quantity' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', "Please enter all required fields");
        }
        if ($id == null) {
            $voucher = Voucher::create([
                'title' => $request->title,
                'code' => Str::upper(Str::random(12)),
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'min_price' => $request->min_price,
                'discount_amount' => $request->discount_amount,
                'quantity' => $request->quantity
            ]);
            return redirect()->back()->with('success', 'Create new voucher successfully');
        } else {
            $voucher = Voucher::find($id);
            if (!$voucher) {
                return redirect()->back()->with('error', 'Voucher not found');
            }
            $voucher->title = $request->title;
            $voucher->date_start = $request->date_start;
            $voucher->date_end = $request->date_end;
            $voucher->min_price = $request->min_price;
            $voucher->discount_amount = $request->discount_amount;
            $voucher->quantity = $request->quantity;
            $voucher->save();
            return redirect()->back()->with('success', 'Update voucher information successfully');
        }
    }
    public function delete($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Voucher Id is required');
        }
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return redirect()->back()->with('error', 'Voucher not found');
        }
        $voucher->delete();
        return redirect()->back()->with('success', 'Delete voucher successfully');
    }
}
