<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;
use App\Models\Software;
use Illuminate\Support\Facades\File;


class CreateDevice
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $device = Device::create([
            'name' => $args['name'],
            'port' => $args['port'],
            'device_type_id' => $args['device_type_id']
        ]);

        $device->software()->sync($args['software']);

        foreach($args['software'] as $soft) {
            $software = Software::where('id', $soft)->first();

            if (!File::exists(base_path().'/server_scripts/'.$device->deviceType->name.'/'.$software->name)) {
                File::makeDirectory(base_path().'/server_scripts/'.$device->deviceType->name.'/'.$software->name, 0755, true);
                File::makeDirectory(base_path().'/config/devices/'.$device->deviceType->name.'/'.$software->name, 0755, true);
                $this->createInputConfig($device->deviceType->name, $software->name);
            }

        }

        if (!File::exists(base_path().'/config/devices/'.$device->deviceType->name.'/output.php')) {
            $this->createOutputConfig($device->deviceType->name);
        }

        return $device;
    }



    private function createInputConfig(string $deviceTypeName, string $softwareName): void {
        $inputTemplate = "<?php \n \treturn [ \n \t \t'start'  => [], \n \t \t'change'  => [], \n \t \t'stop' => [] \n \t];";
        $file = fopen(base_path().'/config/devices/'.$deviceTypeName.'/'.$softwareName.'/input.php', "w");
        fwrite($file, $inputTemplate);
        fclose($file);
    }

    private function createOutputConfig(string $deviceTypeName): void {
        $outputTemplate = "<?php \n \treturn [];";
        $file = fopen(base_path().'/config/devices/'.$deviceTypeName.'/output.php', "w");
        fwrite($file, $outputTemplate);
        fclose($file);
    }




}
