<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Mockery\Undefined;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use SebastianBergmann\FileIterator\Facade\Config;

class GetConfigByDeviceType
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function __invoke($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $configInput = $args['configInput'];
        $deviceType = $configInput['deviceName'];
        $software = $configInput['software'];

        $localInput = config("devices.$deviceType.$software.localInput");
        $items = config("devices.$deviceType.$software.input");
        if ($localInput != null) {
            $keys = array_keys($items);
            $respArray = [];
            foreach($keys as $key) {
                if ($key == "start") {
                    $respArray['startLocal'] = [...$items[$key], ...$localInput['startLocal']];
                } else {
                    $respArray[$key] = [...$items[$key], ...$localInput[$key]];
                }
            }
            $localInput = $respArray;
        } else {
            $localInput = $items;
            $localInput['startLocal'] = $localInput['start'];
            unset($localInput['start']);
        }

        if ($localInput) {
            $keys = array_keys($localInput);
            $respItems = [];
            foreach($keys as $key) {
                $respItem['scriptName'] = $key;
                $respItem['items'] = $localInput[$key];
                array_push($respItems, $respItem);
            }

            return [
                "items" => $respItems
            ];
        } else {
            return [
                "items" => []
            ];
        }
    }
}
