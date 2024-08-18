<?php

namespace App\Services;

use Google_Client;
use Google_Service_YouTube;

class YouTubeService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setDeveloperKey(config('services.youtube.api_key'));
    }

    public function getVideoThumbnail($videoId)
    {
        $youtube = new Google_Service_YouTube($this->client);

        $response = $youtube->videos->listVideos('snippet', [
            'id' => $videoId,
        ]);
        if (empty($response->items)) {
            return null;
        }
        return $response->items[0]->snippet->thumbnails->default->url ?? null;
    }
    public function getVideoInfo($videoId)
    {
        $youtube = new Google_Service_YouTube($this->client);

        // Fetch video details
        $response = $youtube->videos->listVideos('snippet,contentDetails,statistics', [
            'id' => $videoId,
        ]);

        if (empty($response->items)) {
            return null;
        }

        $video = $response->items[0];

        // Format video information
        return [
            'title' => $video->snippet->title,
            'thumbnail' => $video->snippet->thumbnails->default->url,
            'duration' => $this->convertDuration($video->contentDetails->duration),
        ];
    }
    private function convertDuration($duration)
    {
        $interval = new \DateInterval($duration);
        $hours = $interval->h;
        $minutes = $interval->i;
        $seconds = $interval->s;

        $totalSeconds = $interval->h * 3600 +$interval->i * 60+ $interval->s;

        // Format the duration as H:MM:SS or MM:SS
        $data['time'] = ($hours > 0 ? $hours . ':' : '') . 
               str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . 
               str_pad($seconds, 2, '0', STR_PAD_LEFT);
        $data['total_seconds'] = $totalSeconds;
        return $data;
    }
}
