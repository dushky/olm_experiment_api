<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
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
        $deviceType = $configInput['deviceType'];
        $software = $configInput['software'];

        $items = config("devices.$deviceType.$software.input");
        // dd(["items" => collect($items)]);
        $keys = array_keys($items);
        $respItems = [];
        foreach($keys as $key) {
            $respItem['scriptName'] = $key;
            $respItem['items'] = $items[$key];
            array_push($respItems, $respItem);
        }
        // dd(["items" => collect($respItems)]);
        return [
            "items" => $respItems
        ];
    }
}
