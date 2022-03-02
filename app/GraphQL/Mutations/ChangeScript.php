<?php

namespace App\GraphQL\Mutations;
use App\Models\Device;
use App\Models\ExperimentLog;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;


class ChangeScript
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        set_time_limit(100);
        $device = Device::find($args['runScriptInput']['device']['deviceID']);
        $deviceName = $args['runScriptInput']['device']['deviceName'];
        $software = $args['runScriptInput']['device']['software'];
        $path = "../server_scripts/$deviceName/$software/change.py";

        $process = new Process([
            "./$path",
            '--port', $device->port,
            '--input', $args['runScriptInput']['inputParameter']
        ]);

        $process->start();
        sleep(1);

        Log::channel('server')->info("CHANGESCRIPT: " . $process->getOutput());
        Log::channel('server')->info("CHANGESCRIPTERROR: " . $process->getErrorOutput());
    }
}
