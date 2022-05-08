<?php

namespace App\GraphQL\Mutations;
use App\Events\DataBroadcaster;
use App\Models\Device;
use App\Models\ExperimentLog;
use Symfony\Component\Process\Process;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Log;


class ChangeScript
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
        $software = $args['runScriptInput']['device']['software'];
        $experimentID = $args['runScriptInput']['experimentID'];
        $scriptName = $args['runScriptInput']['scriptName'];
        $experiment = ExperimentLog::find($experimentID);
        
        if (!posix_getpgid($experiment->process_pid)) {
            return [
                'status' => 'error',
                'experimentID' => $experimentID,
                'errorMessage' => "Experiment is finished!"
            ];
        }

        $scriptFileName = Helpers::getScriptName($scriptName, base_path()."/server_scripts/$deviceName/$software");
        if ($scriptFileName == null) {
            broadcast(new DataBroadcaster(null, $device->name, "No such script or file in directory", false));
            return;
        }
        $path = "../server_scripts/$deviceName/$software/".$scriptFileName;

        if ($software != "openloop") {
            $schemaFileName = explode(".", $experiment->schema_name);
            $args['runScriptInput']['inputParameter'] = $args['runScriptInput']['inputParameter'] . ",file_name:". $schemaFileName[0];
        }
        // Log::channel('server')->info("CHANGE");

        $process = new Process([
            "./$path",
            '--port', $device->port,
            '--input', $args['runScriptInput']['inputParameter']
        ]);

        $process->start();
        sleep(1);

        Log::channel('server')->info("CHANGE: " . $process->getOutput());
        Log::channel('server')->info("CHANGE: " . $process->getErrorOutput());

        if ($process->getErrorOutput())
            return [
                'status' => 'error',
                'experimentID' => $experimentID,
                'errorMessage' => $process->getErrorOutput()
            ];
        else 
            return [
                'status' => 'success',
                'experimentID' => $experimentID,
                'errorMessage' => ''
            ];
    }
}
