<?php

namespace App\GraphQL\Queries;

use App\Models\ExperimentLog;
use App\Models\Device;

class ExperimentDetails
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
        ini_set('memory_limit', '2048M');
        set_time_limit(100);
        $experiment = ExperimentLog::find($args['experimentID']);
        $status = "";
        if ($experiment->timedout_at) {
            $status = "failed";
        }
        else if ($experiment->started_at && ($experiment->finished_at || $experiment->stopped_at)) {
            $status = "finished";
        }
        else if ($experiment->started_at && (!$experiment->finished_at || !$experiment->stopped_at)) {
            $status = "running";
        }
        else {
            $status = "failed";   
        }

        $device = Device::find($experiment->device_id);
        $deviceType = $device->deviceType->name;
        $output = config("devices.". $deviceType .".output");

        $data = file_get_contents(
            $experiment->output_path,
            false,
            null,
            0
        );
        
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
                            "values" => [$splitLine[$i]]
                        ]);
                    } else {
                        array_push($dataToBroadcast[$index]['values'], $splitLine[$i]);
                    }
                }
            }
        }

        $header = [];
        foreach($output as $outputType) {
            array_push($header, $outputType['title']);
        }
        $fp = fopen(storage_path("/app/public/$experiment->id.csv"), 'w');
        fputcsv($fp, $header);
        foreach($split as $line) {
            $line = explode(",", $line);
                fputcsv($fp, $line);
        }
        fclose($fp);


        $array = $this->createReturnArray($dataToBroadcast);
        $returnArray = [
            "url" => url('/storage/'.$experiment->id.'.csv'),
            'status' => $status,
            "values" => $array
        ];

        return $returnArray;
    }

    private function checkKey($dataToBroadcast, $name) {
        foreach($dataToBroadcast as $index => $data) {
            if ($data['name'] == $name) {
                return $index;
            }
        }
        return -1;
    }

    private function createReturnArray($dataToBroadcast) {
        $returnArray = [];
        foreach($dataToBroadcast as $index => $data) {
            array_push($returnArray, [
                'name' => $data['name'],
                'data' => $this->parseValues($data['values'])
            ]);
        }
        return $returnArray;
    }

    private function parseValues($values) {
        $returnArray = [];
        foreach($values as $value) {
            array_push($returnArray, (float) $value);
        }
        return $returnArray;
    }
}
