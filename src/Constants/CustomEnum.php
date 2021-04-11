<?php

namespace App\Constants;

use App\Helpers;
use BenSampo\Enum\Enum;
use VIITech\Helpers\GlobalHelpers;

/**
 * Class CustomEnum
 * @package App\Constants
 */
class CustomEnum extends Enum
{
    /**
     * All
     * @return array
     */
    static function all()
    {
        $public_constants = self::getKeys();
        $result = [];
        foreach ($public_constants as $key => $value) {
            $result[$key] = GlobalHelpers::readableText($value);
        }
        return $result;
    }

}
