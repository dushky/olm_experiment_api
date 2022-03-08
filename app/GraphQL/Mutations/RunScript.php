<?php

namespace App\GraphQL\Mutations;

use App\Events\DataBroadcaster;
use App\Models\Device;
use App\Models\ExperimentLog;
use App\Jobs\StartReadingProcess;
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
        $fileName = '/home/iolab/Desktop/output.txt';
        file_put_contents($fileName, "");
        set_time_limit(100);
        $date = filemtime($fileName);

        $device = Device::find($args['runScriptInput']['device']['deviceID']);
        $deviceName = $args['runScriptInput']['device']['deviceName'];
        $software = $args['runScriptInput']['device']['software'];
        $scriptName = $args['runScriptInput']['scriptName'];
        $path = base_path()."/server_scripts/$deviceName/$software/$scriptName.py";
        $args['runScriptInput']['fileName'] = "D6223";
        $args['runScriptInput']['inputParameter'] = $args['runScriptInput']['inputParameter'] . ",uploaded_file:". storage_path('tmp/uploads/') . ",file_name:".$args['runScriptInput']['fileName'];
        
        $experiment = ExperimentLog::create([
            'device_id' => $device->id,
            'input_arguments' => $args['runScriptInput']['inputParameter'],
            'output_path' => $fileName,
            'process_pid' => '',
            'started_at' => date("Y-m-d H:i:s")
        ]);

        
        // return ['output' => $args['runScriptInput']['inputParameter']];
        $readingProcess = new StartReadingProcess($date, $fileName, $path, $device, $args, $experiment);
        dispatch($readingProcess);
        
        return ['output' => 'success'];
    }
}
