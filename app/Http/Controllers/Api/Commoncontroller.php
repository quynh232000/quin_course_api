<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Response;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;



class Commoncontroller extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/common/banks",
     *      operationId="getbanks",
     *      tags={"Commons"},
     *      summary="Get All Banks Information",
     *      description="Returns Bank information",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * )
     */
    public function getBanks()
    {
        $allBanks = Bank::all();
        return Response::json(true, 'ok', $allBanks);
    }
 
}
