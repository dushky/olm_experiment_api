<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;
use App\Models\Software;
use Illuminate\Support\Facades\File;


class UpdateDevice
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
        $device = Device::where('id', $args['id'])->first();

        $cameraPort = trim($args['camera_port']);
        $device->update([
                'name' => $args['name'],
                'device_type_id' => $args['deviceType'],
                'port' => $args['port'],
                'camera_port' => $cameraPort ?: null
            ]);
        // dd($device);
        $device->software()->sync($args['software']);
        $device = $device->fresh();
        foreach($args['software'] as $soft) {
            $software = Software::where('id', $soft)->first();

            if (!File::exists(base_path().'/server_scripts/'.$device->deviceType->name.'/'.$software->name)) {
                File::makeDirectory(base_path().'/server_scripts/'.$device->deviceType->name.'/'.$software->name, 0755, true);
            }

        }

        return $device;
    }
}
