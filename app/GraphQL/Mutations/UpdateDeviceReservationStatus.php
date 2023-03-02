<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;

class UpdateDeviceReservationStatus
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        $reservationStatus = $args['deviceReservationStatusInput'];
        $device = Device::query()->find($reservationStatus["deviceID"]);

        if ($device) {
            $device->is_reserved = $reservationStatus["isReserved"];
        }

        if (!$device->isDirty()) {
            return ['updatedDeviceCount' => 0];
        }

        $device->save();

        return ['updatedDeviceCount' => 1];


    }
}
