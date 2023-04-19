<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;
use Symfony\Component\Process\Process;

class StartVideoStream
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $device = Device::query()->findOrFail($args['deviceID']);

        if (!$device->camera_port) {
            return ["isRunning" => false, "status" => "Camera port is NOT set"];
        }

        $process = Process::fromShellCommandline("ffmpeg -i $device->camera_port -c:v libx264 -x264opts keyint=60:no-scenecut -b:v 125k -c:a copy -an -s 426x240 -r 30 -sws_flags bilinear -tune zerolatency -preset veryfast -f flv rtmp://localhost/hls/$device->name");

        $process->start();

        sleep(1);

        $device->video_process_id = $process->getPid() + 1;

        $device->save();

        return ["isRunning" => $process->isStarted(), "status" => "Video stream is running"];

    }
}
