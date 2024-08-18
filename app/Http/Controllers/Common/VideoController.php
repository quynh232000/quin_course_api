<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

use App\Models\Response;
use App\Services\YouTubeService;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $youtube;

    public function __construct(YouTubeService $youtube)
    {
        $this->youtube = $youtube;
    }
    /**
* @OA\Get(

   *      path="/api/common/video/getinfo/{id}",
  *      operationId="getvideoinfo",
  *      tags={"Common"},
  *      summary="Get video information by id",
  *      description="Returns video information",
*    @OA\Parameter(
*          name="id",
*          description="id video",
*          required=true,
*          in="path",
*          @OA\Schema(
*              type="string"
*          )
*      ),
*     @OA\Response(
*         response=400,
*         description="Invalid ID supplied"
*     ),
* 
*     
* )
*/
    public function getVideoInfo($id)
    {
        // $thumbnailUrl = $this->youtube->getVideoThumbnail($videoId);
        $info = $this->youtube->getVideoInfo($id);
        if (!$info) {
            return Response::json(false, 'Video not fund', null, null, 401);
        }
        return Response::json(true, 'Get video infomation by id successfully', $info);
    }
}
