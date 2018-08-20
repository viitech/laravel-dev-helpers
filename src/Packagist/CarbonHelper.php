<?php

namespace VIITech\Helpers\Packagist;

use Carbon\Carbon;
use Exception;
use MongoDB\BSON\UTCDateTime;

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
                if (is_array($value) && isset($value["date"])) {
                    return $value["date"];
                }
                return Carbon::instance($value->toDateTime())->format('c');
            }
            return $value;
        } catch (Exception $e) {
            return $value;
        }
    }
}