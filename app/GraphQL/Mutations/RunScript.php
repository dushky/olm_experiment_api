<?php

namespace App\GraphQL\Mutations;

use App\Events\DataBroadcaster;
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
        $process = new Process([
            'python3', 'test.py',
            '--port', '/dev/ttyACM0',
            '--output', $fileName,
            '--input', 'reg_request:30,input_fan:0,input_lamp:0,input_led:0,t_sim:10,s_rate:200,Ks:0.1,Tdm:0.3,KP:33.33,p1:6.67,p2:111.11'
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
