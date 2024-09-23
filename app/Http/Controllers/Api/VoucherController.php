<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Voucher;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/voucher",
     *      operationId="get_list_voucher",
     *      tags={"Voucher"},
     *      summary="get_list_voucher ",
     *      description="get_list_voucher",
     *      @OA\Response(
     *          response=400,
     *          description="Invalid ID supplied"
     *      ),
     * )
     */
    public function get_list(Request $request)
    {
        try {
            $limit = $request->limit?? 10;
            $data = Voucher::where('date_start', '<=', Carbon::now())
            ->where('date_end', '>', Carbon::now())->limit($limit)
            ->get();
            // return Carbon::now();
            // $data = Voucher::all();
            return Response::json(true, 'Get list voucher successfully!', $data);
        } catch (Exception $e) {
            return Response::json(false, 'Error:' . $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *      path="/api/voucher/check/{code}",
     *      operationId="check_voucher",
     *      tags={"Voucher"},
     *      summary="check_voucher ",
     *      description="check_voucher",
     *      @OA\Parameter(
     *         description="code of this course",
     *         in="path",
     *         name="code",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid ID supplied"
     *      ),
     * )
     */
    public function check_voucher($code)
    {
        try {
            if (!$code) {
                return Response::json(false, 'Invalid code supplied');
            }
            $voucher = Voucher::where('code',$code)->first();
            if (!$voucher) {
                return Response::json(false, 'Voucher not found');
            }
            if ($voucher->used >= $voucher->quantity) {
                return Response::json(false, 'Voucher has been used up');
            }
            if ($voucher->date_start > Carbon::now()) {
                return Response::json(false, 'Voucher is not available yet');
            }
            if ($voucher->date_end < Carbon::now()) {
                return Response::json(false, 'Voucher has expired');
            }
            return Response::json(true, 'Voucher is valid');
        } catch (Exception $e) {
            return Response::json(false, 'Error:' . $e->getMessage());
        }
    }
}
