<?php

namespace App\GraphQL\Mutations;

use Symfony\Component\Process\Process;

class StartVideoStream
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $process = Process::fromShellCommandline("ffmpeg -i /dev/video0 -c:v libx264 -x264opts keyint=60:no-scenecut -b:v 125k -c:a copy -an -s 426x240 -r 30 -sws_flags bilinear -tune zerolatency -preset veryfast -f flv rtmp://localhost/hls/experiment");

        $process->start();

        return ["isRunning" => $process->isStarted()];

    }
}
