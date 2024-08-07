<?php
namespace App\Models;
class Response
{
    private $status;
    private $message;
    private $data;
    private $meta;
    private $links;

    public function __construct($status, $message, $data = null, $meta = null, $links = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->meta = $meta;
        $this->links = $links;
    }

    public static function json($status, $message, $data = null, $meta = null, $links = null, $status_response = 200)
    {
        $response = new Response($status, $message, $data, $meta, $links);
        return $response->toJson($status_response);
    }

    public function toJson($status_response = 200)
    {
        return response()->json([
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'meta' => $this->meta,
            'links' => $this->links
        ], $status_response);
    }

}