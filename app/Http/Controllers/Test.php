<?php

namespace App\Http\Controllers;
use Symfony\Component\Process\Process;
use App\Events\DataBroadcaster;


class Test extends Controller
{
    public string $fileName = '/home/item/Desktop/output.txt';
    public function index() : void {
        $data = file_get_contents(
            $this->fileName,
            false,
            null,
            0
        );
        $split = explode("\n", $data);
        $dataToBroadcast = [];
        $output = config("devices.tos1a.output");

        foreach($split as $line) {
            if ($line != "") {
                $splitLine = explode(",", $line);
                for($i = 0; $i < count($splitLine); $i++) {
                    $index = $this->checkKey($dataToBroadcast, $output[$i]['title']);
                    if ($index == -1) {
                        array_push($dataToBroadcast, [
                            "name" => $output[$i]['title'],
                            "data" => [$splitLine[$i]]
                        ]);
                    } else {
                        array_push($dataToBroadcast[$index]['data'], $splitLine[$i]);
                    }
                }
            }
        }
        // dd($dataToBroadcast);
        // dd($dataToBroadcast);
        broadcast(new DataBroadcaster($dataToBroadcast));
        // set_time_limit(100);
        // $date = filemtime($this->fileName);
        // $lastDataLength = 0;
        // $process = new Process([
        //     'python3', 'test.py',
        //     '--port', '/dev/ttyACM0',
        //     '--output', '/home/martin/Desktop/output.txt',
        //     '--input', 'reg_request:30,input_fan:0,input_lamp:0,input_led:0,t_sim:10,s_rate:200,Ks:0.1,Tdm:0.3,KP:33.33,p1:6.67,p2:111.11'
        // ]);

        // $process->start();
        // sleep(1);

        // while($process->isRunning()) {
        //     clearstatcache();
        //     if ($date != filemtime($this->fileName)) {
        //         $date = filemtime($this->fileName);
        //         $data = file_get_contents(
        //             $this->fileName,
        //             false,
        //             null,
        //             $lastDataLength
        //         );
        //         $lastDataLength += strlen($data);
        //         broadcast(new DataBroadcaster($data));
        //     }
        // };
        // dd($process->getErrorOutput());
    }

    private function checkKey($dataToBroadcast, $name) {
        foreach($dataToBroadcast as $index => $data) {
            if ($data['name'] == $name) {
                return $index;
            }
        }
        return -1;
    }
}
