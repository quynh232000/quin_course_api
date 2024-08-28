<?php
namespace App\Models;
class Response
{
    private $status;
    private $message;
    private $data;
    private $meta;

    public function __construct($status, $message, $data = null, $meta = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->meta = $meta;
    }

    public static function json($status, $message, $data = null, $meta = null, $status_response = 200)
    {
        $response = new Response($status, $message, $data, $meta);
        return $response->toJson($status_response);
    }
    public static function pagination($data)
    {
        return [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'next_page_url' => $data->nextPageUrl(),
            'prev_page_url' => $data->previousPageUrl(),
        ];
    }
    public function toJson($status_response = 200)
    {
        return response()->json([
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'meta' => $this->meta
        ], $status_response);
    }

}