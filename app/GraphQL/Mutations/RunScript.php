<?php

namespace App\GraphQL\Mutations;

use App\Events\DataBroadcaster;
use App\Models\Device;
use App\Models\ExperimentLog;
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

        // dd($args);
        $fileName = '/home/item/Desktop/output.txt';
        set_time_limit(100);
        $date = filemtime($fileName);
        $lastDataLength = 0;
        // dd($args);
        $device = Device::find($args['runScriptInput']['device']['deviceID']);
        $deviceName = $args['runScriptInput']['device']['deviceName'];
        $software = $args['runScriptInput']['device']['software'];
        $scriptName = $args['runScriptInput']['scriptName'];
        $path = "../server_scripts/$deviceName/$software/$scriptName.py";

        $process = new Process([
            "./$path",
            '--port', $device->port,
            '--output', $fileName,
            '--input', $args['runScriptInput']['inputParameter']
        ]);

        $process->start();
        sleep(1);
        $output = config("devices.tos1a.output");

        $experiment = ExperimentLog::create([
            'device_id' => $device->id,
            'input_arguments' => $args['runScriptInput']['inputParameter'],
            'output_path' => $fileName,
            'process_pid' => $process->getPid(),
            'started_at' => date("Y-m-d H:i:s")
        ]);

        while($process->isRunning()) {
            clearstatcache();
            if ($date != filemtime($fileName)) {
                $date = filemtime($fileName);
                $data = file_get_contents(
                    $fileName,
                    false,
                    null,
                    0
                );
                $lastDataLength += strlen($data);
                $split = explode("\n", $data);
                $dataToBroadcast = [];

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
                broadcast(new DataBroadcaster($dataToBroadcast));
            }
        };

        $experiment = $experiment->fresh();
        if(!isset($experiment->stopped_at))
            $experiment->update([
                'finished_at' => date("Y-m-d H:i:s")
            ]);
        // Log::channel('server')->info($process->getErrorOutput());
        // Log::channel('server')->info("PROCESS OUTPUT: " . date("Y-m-d H:i:s"));
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
