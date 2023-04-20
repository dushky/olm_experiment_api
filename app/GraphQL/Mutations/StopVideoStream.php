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

        $process = Process::fromShellCommandline("pkill -f '^ustreamer-$device->name'");
        $process->run();

        sleep(1);

        if (!$process->isSuccessful()) {
            return ['isStopped' => false, 'status' => $process->getErrorOutput()];
        }

        return ['isStopped' => true, 'status' => $process->getOutput()];
    }
}
