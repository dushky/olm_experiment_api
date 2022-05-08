<?php

namespace App\Http\Controllers;

use App\Models\ExperimentLog;
use App\Models\Device;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request)
    {
        //  Store files temporarily in storage/tmp/uploads
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');
        $extension = explode(".", trim($file->getClientOriginalName()));
        $name = "";
        
        if ($extension[count($extension) - 1] == "slx")
            $name = "M" . uniqid() . ".slx";
        else if ($extension[count($extension) - 1] == "xcos")
            $name = "S" . uniqid() . ".xcos";
        else 
            $name = "O" . uniqid() . ".".$extension[count($extension) - 1];

        $file->move($path, $name);

        return response()->json([
            'name' => $name
        ]);
    }

    public function download(Request $request, $id) {
        ini_set('memory_limit','5000000M');
        set_time_limit(0);
        $experiment = ExperimentLog::find($id);
        $device = Device::find($experiment->device_id);
        $deviceType = $device->deviceType->name;
        $output = config("devices.".$deviceType.".output");
        $header = [];
        foreach($output as $outputType) {
            array_push($header, $outputType['name']);
        }

        $fp = fopen('file.csv', 'w');

        $data = file_get_contents(
            $experiment->output_path,
            false,
            null,
            0
        );

        $split = explode("\n", $data);
        $fp = fopen('file.csv', 'w');
        fputcsv($fp, $header);
        foreach($split as $line) {
            $line = explode(",", $line);
                fputcsv($fp, $line);
        }

        fclose($fp);
        
        return response()->download('file.csv', "experimentOutput.csv");
    }
}
