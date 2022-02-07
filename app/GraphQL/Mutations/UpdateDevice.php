<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;

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
        $device->update([
                'name' => $args['name'],
                'device_type_id' => $args['deviceType'],
                'port' => $args['port']
            ]);
        // dd($device);
        $device->software()->sync($args['software']);

        return $device;
    }
}
