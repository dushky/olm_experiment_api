<?php

namespace App\GraphQL\Mutations;

use App\Events\DataBroadcaster;
use App\Models\Device;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;


class RunScript
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $fileName = '/home/martin/Desktop/output.txt';
        set_time_limit(100);
        $date = filemtime($fileName);
        $lastDataLength = 0;

        $device = Device::find($args['runScriptInput']['device']['deviceID']);

        dd($args);
        

        $process = new Process([
            'python3', 'test.py',
            '--port', $device->port,
            '--output', $fileName,
            '--input', $args['runScriptInput']['inputParameter']
        ]);
        
        $process->start();
        sleep(1);

        while($process->isRunning()) {
            clearstatcache();
            if ($date != filemtime($fileName)) {
                $date = filemtime($fileName);
                $data = file_get_contents(
                    $fileName,
                    false,
                    null,
                    $lastDataLength
                );
                $lastDataLength += strlen($data);
                broadcast(new DataBroadcaster($data));
            }
        };
    }
}
