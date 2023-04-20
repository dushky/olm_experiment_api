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

        $process = Process::fromShellCommandline("pgrep -f '^ustreamer-$device->name' && echo stream is running || echo stream is NOT running");
        $process->run();

        $isRunning = false;
        if (!$process->isSuccessful()) {
            return ['isRunning' => $isRunning, 'status' => $process->getErrorOutput()];
        }

        $output = trim($process->getOutput());
        if (str_contains($output, 'stream is running')) {
            $isRunning = true;
            $output = 'stream is running';
        }
        else {
            $output = 'stream is NOT running';
        }

        return ['isRunning' => $isRunning, 'status' => $output];
    }
}
