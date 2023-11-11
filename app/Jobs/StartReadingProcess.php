<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use App\Models\ExperimentLog;
use App\Models\Device;
use Illuminate\Support\Facades\Log;
use App\Events\DataBroadcaster;
use App\Helpers\Helpers;

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
        // CHECK INIT - initFile does not exist in l3DSquare
        if (Helpers::checkIfInitIsAvailable(base_path()."/server_scripts/$this->deviceType")) {
            $initFile = Helpers::getScriptName("init", base_path()."/server_scripts/$this->deviceType");

            if ($initFile == null) {
                broadcast(new DataBroadcaster(null, $this->device->name, "No such script or file in directory", false));
                return;
            }

            $process = new Process([
                base_path()."/server_scripts/$this->deviceType/".$initFile,
            ]);

            $process->start();

            sleep(1);

            if ($process->getPid() == null) {
                Log::debug("process = null");
                broadcast(new DataBroadcaster(null, $this->device->name, $process->getErrorOutput(), false));
            }

            $process->wait();
        }


        $lastDataLength = 0;
        Log::debug("path: ", [$this->path]);
        Log::debug("device port", [$this->device->port]);
        Log::debug("output", [$this->fileName]);
        Log::debug("input", [$this->args['runScriptInput']['inputParameter']]);

        $process = new Process([
            "$this->path",
            '--port', $this->device->port,
            '--output', $this->fileName,
            '--input', $this->args['runScriptInput']['inputParameter']
        ]);

        $process->start();
        sleep(1);
        $output = config("devices.".$this->deviceType.".output");

        if ($process->getPid() != null) {
            $this->experiment->update([
                'process_pid' => $process->getPid()
            ]);
        } else {
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

    public function failed() {
        $this->experiment->update([
            'timedout_at' => date("Y-m-d H:i:s")
        ]);
        $this->delete();
    }

    private function formatDataToWebsockets(Array $split, Array $output) {
        $dataToBroadcast = [];
        foreach($split as $line) {
            if ($line != "") {
                $splitLine = explode(",", $line);
                for($i = 0; $i < count($splitLine); $i++) {
                    $index = $this->checkKey($dataToBroadcast, $output[$i]['title']);
                    if ($index == -1) {
                        array_push($dataToBroadcast, [
                            "name" => $output[$i]['title'],
                            "tag" => $output[$i]['name'],
                            "defaultVisibilityFor" => $output[$i]['defaultVisibilityFor'] ?? [],
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
