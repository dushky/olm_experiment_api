<?php

namespace App\GraphQL\Mutations;

use App\Models\Device;

class UpdateDevicesReservationStatus
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        $updatedDevicesCount = 0;
        collect($args['devicesReservationStatusInput'])->each(function ($reservationStatus) use ($updatedDevicesCount) {
            $device = Device::query()->find($reservationStatus["deviceID"]);
            if ($device) {
                $device->is_reserved = $reservationStatus["isReserved"];
            }

            if ($device->isDirty()) {
                $updatedDevicesCount ++;
            }

            $device->save();
        });

        return ['updatedDevicesCount' => $updatedDevicesCount];
    }
}
