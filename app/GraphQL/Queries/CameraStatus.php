<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class CameraStatus
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {

        $process = Process::fromShellCommandline("test -e /dev/video0 && echo is connected || echo is NOT connected");
        $process->run();

        $isConnected = false;
        if (!$process->isSuccessful()) {
            return ['isConnected' => $isConnected, 'status' => $process->getErrorOutput()];
        }

        $output = trim($process->getOutput());
        if ($output === 'is connected') {
            $isConnected = true;
        }

        return ['isConnected' => $isConnected, 'status' => $output];


    }
}
