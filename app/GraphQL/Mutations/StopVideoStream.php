<?php

namespace App\GraphQL\Mutations;

use Symfony\Component\Process\Process;

class StopVideoStream
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $process = Process::fromShellCommandline("killall ffmpeg");
        $process->run();

        if (!$process->isSuccessful()) {
            return ['isStopped' => false, 'status' => $process->getErrorOutput()];
        }

        return ['isStopped' => true, 'status' => $process->getOutput()];
    }
}
