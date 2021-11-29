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
            '--input', 'reg_request:30,input_fan:20,input_lamp:20,input_led:20,t_sim:10,s_rate:200'
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
