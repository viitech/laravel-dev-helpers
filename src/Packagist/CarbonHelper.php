<?php

namespace VIITech\Helpers\Packagist;

use Carbon\Carbon;
use Exception;
use MongoDB\BSON\UTCDateTime;
use VIITech\Helpers\Constants\Attributes;
use VIITech\Helpers\Constants\EnvVariables;

class CarbonHelper
{

    /**
     * Generate Now Timestamp
     * @param string $format
     * @return string
     */
    static function generateNowTimestamp($format = 'c')
    {
        return Carbon::now(env(EnvVariables::APP_TIMEZONE))->format($format);
    }

    /**
     * Get Formatted Carbon Date From UTC DateTime
     * @param UTCDateTime $value
     * @param string $format
     * @return mixed
     */
    static function getFormattedCarbonDateFromUTCDateTime($value, $format = 'c')
    {
        try {
            if (!is_null($value)) {
                if (is_array($value) && isset($value[Attributes::DATE])) {
                    return $value[Attributes::DATE];
                }
                return Carbon::instance($value->toDateTime())->format($format);
            }
            return $value;
        } catch (Exception $e) {
            return $value;
        }
    }
}