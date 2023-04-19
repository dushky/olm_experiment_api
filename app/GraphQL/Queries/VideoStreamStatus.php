<?php

namespace App\GraphQL\Queries;

use App\Models\Device;
use Symfony\Component\Process\Process;

class VideoStreamStatus
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $device = Device::query()->findOrFail($args['deviceID']);

        $isRunning = ($device->video_process_id !== null);
        $output = "Stream is NOT running";

        if ($isRunning) {
            $output = "Stream is running";
        }

        return ['isRunning' => $isRunning, 'status' => $output];
    }
}
