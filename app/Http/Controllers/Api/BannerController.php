<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Response;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/banners",
     *      operationId="getallbanner",
     *      tags={"Banner"},
     *      summary="Get all banners",
     *      description="Returns list banners",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *      @OA\Parameter(
     *         description="Where banner will show. Ex: home,detail,...",
     *         in="query",
     *         name="placement",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * *      @OA\Parameter(
     *         description="Position banner will apperance in this page. Ex: slider,ads,...",
     *         in="query",
     *         name="type",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * )
     */
    public function all(Request $request)
    {
        $query = Banner::where('is_show', 1);
        if ($request->placement && $request->placement != '') {
            $query->where('placement', $request->placement);
        }
        if ($request->type && $request->type != '') {
            $query->where('type', $request->type);
        }
        $data = $query->get();
        return Response::json(true, 'Get all banner successfully', $data);
    }
}
