<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;
use Symfony\Component\Process\Process;

class StartVideoStream
{

    const EXIT_ON_NO_CLIENTS_SEC = 120;

    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        $device = Device::query()->findOrFail($args['deviceID']);

        if (!$device->camera_port) {
            return ["isRunning" => false, "status" => "Camera port is NOT set"];
        }

        $process = Process::fromShellCommandline("~/ustreamer/ustreamer --device=$device->camera_port --host=0.0.0.0 --port=9001 --process-name-prefix='ustreamer-$device->name' --exit-on-no-clients=" . self::EXIT_ON_NO_CLIENTS_SEC . " --allow-origin=\*");

        $process->start();
        sleep(1);

        if (!$process->isStarted()) {
            return ["isRunning" => $process->isStarted(), "status" => $process->getErrorOutput()];

        }

        return ["isRunning" => $process->isStarted(), "status" => "Video stream is running"];

    }
}
