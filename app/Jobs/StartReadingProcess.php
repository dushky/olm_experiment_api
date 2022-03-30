<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use App\Models\ExperimentLog;
use App\Models\Device;
use Illuminate\Support\Facades\Log;
use App\Events\DataBroadcaster;
use App\Events\IsFinishedBroadcaster;

class StartReadingProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $date;
    protected $fileName;
    protected $path;
    protected $device;
    protected $args;
    protected $experiment;
    protected $deviceType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    //     public function __construct(Process $process, int $date, string $fileName, ExperimentLog $experiment)
    public function __construct(int $date, string $fileName, string $path, Device $device, $args, ExperimentLog $experiment, string $deviceType)
    {
        $this->date = $date;
        $this->fileName = $fileName;
        $this->path = $path;
        $this->device = $device;
        $this->args = $args;
        $this->experiment = $experiment;
        $this->deviceType = $deviceType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lastDataLength = 0;

        $process = new Process([
            "$this->path",
            '--port', $this->device->port,
            '--output', $this->fileName,
            '--input', $this->args['runScriptInput']['inputParameter']
        ]);

        $process->start();
        sleep(1);
        $output = config("devices.".$this->deviceType.".output");
        
        // Log::channel('server')->error("SKAPALO?: " . $process->getErrorOutput());
        if ($process->getPid() != null) {
            $this->experiment->update([
                'process_pid' => $process->getPid()
            ]);
        } else {
            // Log::channel('server')->error("SKAPALO?: " . $process->getErrorOutput());
            broadcast(new DataBroadcaster(null, $this->device->name, $process->getErrorOutput(), false));
            $this->experiment->update([
                'timedout_at' => date("Y-m-d H:i:s")
            ]);
        }

        $dataToBroadcast = [];
        // Start Reading From File
        while($process->isRunning()) {
            clearstatcache();
            if ($this->date != filemtime($this->fileName)) {
                $this->date = filemtime($this->fileName);
                $data = file_get_contents(
                    $this->fileName,
                    false,
                    null,
                    0
                );
                $lastDataLength += strlen($data);
                $split = explode("\n", $data);
                $dataToBroadcast = [];

                $dataToBroadcast = $this->formatDataToWebsockets($split, $output);

                broadcast(new DataBroadcaster($dataToBroadcast, $this->device->name, null, false));
            }
        };
        
        // Send Last Message with full data
        if (!$process->isRunning() && count($dataToBroadcast) > 0) {
            $data = file_get_contents(
                $this->fileName,
                false,
                null,
                0
            );
            $lastDataLength += strlen($data);
            $split = explode("\n", $data);
            
            $dataToBroadcast = $this->formatDataToWebsockets($split, $output);
            
            broadcast(new DataBroadcaster($dataToBroadcast, $this->device->name, null, false));
            broadcast(new DataBroadcaster($dataToBroadcast, $this->device->name, null, true));
        } else {
            Log::channel('server')->error("SKAPALO AZ NA KONCI?: " . $process->getErrorOutput());
            broadcast(new DataBroadcaster(null, $this->device->name, $process->getErrorOutput(), false));
            broadcast(new DataBroadcaster(null, $this->device->name, $process->getErrorOutput(), true));
            broadcast(null);
        }

        $this->experiment = $this->experiment->fresh();
        if(!isset($this->experiment->stopped_at))
            $this->experiment->update([
                'finished_at' => date("Y-m-d H:i:s")
            ]);
            
        Log::channel('server')->error("ERRORMESSAGE: " . $process->getErrorOutput());
        Log::channel('server')->info("PROCESS OUTPUT: " . $process->getOutput());
    }

    private function checkKey(Array $dataToBroadcast, String $name) {
        foreach($dataToBroadcast as $index => $data) {
            if ($data['name'] == $name) {
                return $index;
            }
        }
        return -1;
    }


    private function formatDataToWebsockets(Array $split, Array $output): Array {
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
        return $dataToBroadcast;
    }
}
