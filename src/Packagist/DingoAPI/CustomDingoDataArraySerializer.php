<?php

namespace VIITech\Helpers\Packagist\DingoAPI;

use League\Fractal\Serializer\ArraySerializer;

class CustomDingoDataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return ($resourceKey) ? [ $resourceKey => $data ] : $data;
    }

    /**
     * Serialize an item.
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return ($resourceKey) ? [ $resourceKey => $data ] : $data;
    }
}