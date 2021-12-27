<?php

namespace App\Http\Controllers;
use Symfony\Component\Process\Process;
use App\Events\DataBroadcaster;


class Test extends Controller
{
    public string $fileName = 'demofile2.txt';
    public function index() : void {
        set_time_limit(100);
        $date = filemtime($this->fileName);
        $lastDataLength = 0;
        $process = new Process([
            'python3', 'test.py',
            '--port', '/dev/ttyACM0',
            '--output', '/home/martin/Desktop/output.txt',
            '--input', 'reg_request:30,input_fan:0,input_lamp:0,input_led:0,t_sim:10,s_rate:200,Ks:0.1,Tdm:0.3,KP:33.33,p1:6.67,p2:111.11'
        ]);
        
        $process->start();
        sleep(1);

        while($process->isRunning()) {
            clearstatcache();
            if ($date != filemtime($this->fileName)) {
                $date = filemtime($this->fileName);
                $data = file_get_contents(
                    $this->fileName,
                    false,
                    null,
                    $lastDataLength
                );
                $lastDataLength += strlen($data);
                broadcast(new DataBroadcaster($data));
            }
        };
        dd($process->getErrorOutput());
    }
}
