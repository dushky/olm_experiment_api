<?php

namespace App\GraphQL\Mutations;
use App\Models\Device;
use App\Models\ExperimentLog;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;


class StopScript
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

        $process = new Process([
            "./../server_scripts/$deviceName/stop.py",
            '--port', $device->port
        ]);

        $process->start();
        sleep(1);
        broadcast(null);

        $experiment = ExperimentLog::where('device_id', $device->id)->orderBy('id', 'DESC')->first();
        // dd($experiment);
        $pid = $experiment->process_pid;
        $allProcesses = $this->getAllChildProcesses($pid);
        $allPids = array_merge([$pid], $allProcesses);

        $experiment->update([
            'stopped_at' => date("Y-m-d H:i:s")
        ]);

        foreach ($allPids as $killpid) {
            $killProcess = new Process([
                "kill","-9","$killpid"
            ]);
            // exec("kill -9 $killpid", $result);

            $killProcess->start();
            sleep(1);
            Log::channel('server')->info("TIME: " . date("Y-m-d H:i:s"));
            Log::channel('server')->info("PID: " . $killpid);
            Log::channel('server')->info("ERROR: " . $killProcess->getErrorOutput());
            Log::channel('server')->info("OUTPUT: " . $killProcess->getOutput());
            // $arguments = [
            //     "-TERM",
            //     $pid
            // ];
        }


    }


    protected function getAllChildProcesses($pid)
    {
        $process = new Process(["pstree -p ". $pid ." | grep -o '([0-9]\+)' | grep -o '[0-9]\+'"]);

        $process->run();
        $allProcesses = array_filter(explode("\n", $process->getOutput()));

        return $allProcesses;
    }
}
