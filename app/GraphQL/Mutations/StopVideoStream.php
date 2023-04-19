<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;
use Symfony\Component\Process\Process;

class StopVideoStream
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $device = Device::query()->findOrFail($args['deviceID']);

        $process = Process::fromShellCommandline("kill -9 $device->video_process_id");
        $process->run();

        sleep(1);

        if (!$process->isSuccessful()) {
            return ['isStopped' => false, 'status' => $process->getErrorOutput()];
        }

        $device->video_process_id = null;
        $device->save();
        return ['isStopped' => true, 'status' => $process->getOutput()];
    }
}
