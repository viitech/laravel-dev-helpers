<?php

namespace VIITech;

namespace VIITech\Helpers;

use Exception;

class GlobalHelpers
{

    /**
     * Check Environment (local, dev, staging, production)
     * @param string $app_env
     * @return bool
     */
    static function checkEnvironment($app_env)
    {
        return app()->environment() === $app_env;
    }

    /**
     * Get Binary Path
     * @param string $binary_name
     * @return string|null
     */
    static function getBinaryPath($binary_name)
    {
        try {
            $result = shell_exec('which ' . $binary_name);
            if ($result != null) {
                return $result;
            }
            return env($binary_name, "");
        } catch (Exception $e) {
            return env($binary_name, "");
        }
    }

    /**
     * Is Valid Object
     * @param $var
     * @param null $type instance of this type
     * @return boolean
     */
    static function isValidObject($var, $type = null)
    {
        if (!is_null($var)) { // variable is not null
            if (!is_null($type)) { // check instance type
                return $var instanceof $type; // is variable instance of type
            } else { // variable is not null, don't check type
                return true;
            }
        } else { // variable is null
            return false;
        }
    }

    /**
     * Return String
     * @param $str
     * @return string|null
     */
    static function returnString($str)
    {
        try {
            if (is_null($str) || empty($str)) {
                return null;
            }
            return (string)$str;
        } catch (Exception $e) {
            return null;
        }
    }

}

