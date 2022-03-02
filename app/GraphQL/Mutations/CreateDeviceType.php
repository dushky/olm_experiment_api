<?php

namespace App\GraphQL\Mutations;

use App\Models\DeviceType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class CreateDeviceType
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $deviceType = DeviceType::create([
            'name' => $args['name']
        ]);

        if (!File::exists(base_path().'/server_scripts/'.$args['name'])) {
            File::makeDirectory(base_path().'/server_scripts/'.$args['name'], 0755, true);
        }


        return $deviceType;
        // TODO implement the resolver
    }
}
