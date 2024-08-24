<?php

if (!function_exists('format_seconds_to_time')) {
    function format_seconds_to_time(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        if ($hours == 0) {
            if ($minutes == 0) {
                return sprintf('%02d seconds', $remainingSeconds);
            }
            return sprintf('%02d:%02d', $minutes, $remainingSeconds);

        }
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }
}