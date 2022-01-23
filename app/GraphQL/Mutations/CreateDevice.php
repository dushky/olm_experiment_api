<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;

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

        return $device;
    }
}
