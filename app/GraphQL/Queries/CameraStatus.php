<?php

namespace App\GraphQL\Queries;

use App\Models\Device;
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
        $device = Device::query()->findOrFail($args['deviceID']);
        $isConnected = false;

        if (!$device->camera_port) {
            return ['isConnected' => $isConnected, 'status' => "Fill camera port in device settings"];
        }
        $device->camera_port = trim($device->camera_port);
        $process = Process::fromShellCommandline("test -e $device->camera_port && echo is connected || echo is NOT connected");
        $process->run();

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
