<?php

namespace VIITech\Helpers\Packagist;

use Carbon\Carbon;
use Exception;
use MongoDB\BSON\UTCDateTime;
use VIITech\Helpers\Constants\Attributes;

class CarbonHelper
{

    /**
     * Get Formatted Carbon Date From UTC DateTime
     * @param UTCDateTime $value
     * @return mixed
     */
    function getFormattedCarbonDateFromUTCDateTime($value)
    {
        try {
            if(!is_null($value)){
                if (is_array($value) && isset($value[Attributes::DATE])) {
                    return $value[Attributes::DATE];
                }
                return Carbon::instance($value->toDateTime())->format('c');
            }
            return $value;
        } catch (Exception $e) {
            return $value;
        }
    }
}