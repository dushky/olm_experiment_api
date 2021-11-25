<?php

namespace App\Http\Controllers;
use Symfony\Component\Process\Process;
use App\Events\DataBroadcaster;


class Test extends Controller
{
    public string $fileName = 'demofile2.txt';
    public function index() : void {
        $date = filemtime($this->fileName);
        $lastDataLength = 0;
        $process = new Process(['python3', 'test.py']);
        
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
    }
}
