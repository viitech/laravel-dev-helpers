<?php

namespace VIITech\Helpers\Packagist\DingoAPI;

use League\Fractal\TransformerAbstract;

abstract class CustomInfoTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        return $item->toArray();
    }
}