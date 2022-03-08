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



class StartReadingProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $date;
    protected $fileName;
    protected $path;
    protected $device;
    protected $args;
    protected $experiment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    //     public function __construct(Process $process, int $date, string $fileName, ExperimentLog $experiment)
    public function __construct(int $date, string $fileName, string $path, Device $device, $args, ExperimentLog $experiment)
    {
        //
        $this->date = $date;
        $this->fileName = $fileName;
        $this->path = $path;
        $this->device = $device;
        $this->args = $args;
        $this->experiment = $experiment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Log::channel('server')->info($this->args);
        $lastDataLength = 0;
        // Log::channel('server')->info("DEVICE: " . $this->device);
        //

        $process = new Process([
            "$this->path",
            '--port', $this->device->port,
            '--output', $this->fileName,
            '--input', $this->args['runScriptInput']['inputParameter']
        ]);

        $process->start();
        sleep(1);
        $output = config("devices.tos1a.output");
        // dd($process->getErrorOutput());
        //TODO IF CANCELED
        Log::channel('server')->info("OUTPUT: " . $process->getErrorOutput());

        if ($process->getPid() != null) {
            $this->experiment->update([
                'process_pid' => $process->getPid()
            ]);
        } else {
            $this->experiment->update([
                'timedout_at' => date("Y-m-d H:i:s")
            ]);
            return;
        }

        while($process->isRunning()) {
            clearstatcache();
            if ($this->date != filemtime($this->fileName) && time() - $this->date >= 3) {
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

        $this->experiment = $this->experiment->fresh();
        if(!isset($this->experiment->stopped_at))
            $this->experiment->update([
                'finished_at' => date("Y-m-d H:i:s")
            ]);
        Log::channel('server')->info($process->getErrorOutput());
        Log::channel('server')->info("PROCESS OUTPUT: " . $process->getOutput());
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
