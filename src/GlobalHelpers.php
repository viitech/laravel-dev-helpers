<?php

namespace VIITech;

namespace VIITech\Helpers;

use DOMDocument, DOMXPath, Exception;

class GlobalHelpers
{

    /**
     * Check Environment (local, dev, staging, production)
     * @param string $app_env
     * @return bool
     */
    public static function checkEnvironment($app_env)
    {
        return app()->environment() === $app_env;
    }

    /**
     * Get Binary Path
     * @param string $binary_name
     * @return string|null
     */
    public static function getBinaryPath($binary_name)
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
    public static function isValidObject($var, $type = null)
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
     * @param mixed $val
     * @return string|null
     */
    public static function returnString($val)
    {
        try {
            if (is_null($val) || empty($val)) {
                return null;
            }
            return (string) $val;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Return Boolean
     * @param mixed $val
     * @return bool
     */
    public static function returnBoolean($val)
    {
        if (is_null($val)) return false; // return false if its null
        if (is_integer($val)) return $val === 1; // convert integer to boolean
        if ($val === "true") {
            $val = true;
        } else if ($val === "false") {
            $val = false;
        }
        return (boolean) $val;
    }

    /**
     * Convert String Array To Integer Array
     * @param array $array
     * @return array
     */
    public static function convertStringArrayToIntegerArray($array = [])
    {
        try {
            foreach ($array AS $index => $value) {
                $array[$index] = !is_integer($value) ? (int) $value : $value;;
            }
        } catch (Exception $e) {
            return null;
        }
        return $array;
    }

    /**
     * Convert Comma Separated String to Array
     * @param string $data
     * @return array|string
     */
    public static function convertCommaSeparatedStringToArray($data = null)
    {
        if(!is_null($data) && is_string($data)){
            return explode(',', $data);
        }
        return $data;
    }

    /**
     * Get Readable Boolean
     * @param bool $bool true (yes) or false (no)
     * @return string Yes or No
     */
    public static function getReadableBoolean($bool)
    {
        if ($bool == 0) {
            return "No";
        } else {
            return "Yes";
        }
    }

    /**
     * Run Command In Server
     * @param $bin
     * @param string $command
     * @param bool $force
     * @return null|string
     */
    public static function runCommandInServer($bin, $command = '', $force = true)
    {
        $stream = null;
        $bin .= $force ? ' 2>&1' : '';

        $descriptorSpec = array
        (
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w')
        );

        $process = proc_open($bin, $descriptorSpec, $pipes);

        if (is_resource($process)) {
            fwrite($pipes[0], $command);
            fclose($pipes[0]);

            $stream = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            proc_close($process);
        }

        return $stream;
    }

    /**
     * Validate variable or return alternative
     * @param $var
     * @param $alt
     * @return object|string|bool|int|float|array
     */
    public static function validateVarWithAlternative($var, $alt)
    {
        if ($var) {
            return $var;
        } else {
            return $alt;
        }
    }

    /**
     * Return Response as JSON
     * @param bool $success
     * @param string $message
     * @param array $data
     * @param int $status
     * @param array $error
     * @return JsonResponse
     */
    public static function returnResponse($success = true, $message = '', $data = [], $error = [], $status = 200)
    {
        if (is_null($data)) {
            $data = [];
        }
        if (is_null($error)) {
            $error = [];
        }

        return response()->json([
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ], $status);
    }

    /**
     * Generate a random number
     * @param int $range_from Range From
     * @param int $range_to Range To
     * @return int
     */
    public static function generateRandomNumber($range_from = 1, $range_to = 10000)
    {
        return rand($range_from, $range_to);
    }

    /**
     * Get Page Title From URL
     * @param $page_url
     * @return string|null
     */
    public static function getPageTitle($page_url){
        if($page_url){
            $doc = new DOMDocument();
            @$doc->loadHTMLFile($page_url);
            $xpath = new DOMXPath($doc);
            return $xpath->query('//title')->item(0)->nodeValue;
        }else{
            return null;
        }
    }

    /**
     * Is String English?
     * @param $str
     * @return boolean
     */
    public static function isEnglish($str)
    {
        if ($str && strlen($str) != strlen(utf8_decode($str))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * URL Exists?
     * @param $url
     * @return boolean
     */
    public static function urlExists($url) {
        if($url) {
            $code = 0;
            try {
                $headers = get_headers($url);
                $code = substr($headers[0], 9, 3);
            } catch (Exception $e) {}
            if ($code == "200" || $code == "302" || $code == "301") { // exists
                return true;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }

    /**
     * Get Web Page Content
     * @param $url
     * @return string|object|null
     */
    public static function getWebPageContent($url) {
        if($url) {
            $content = null;
            $options = array(
                CURLOPT_RETURNTRANSFER => true,   // return web page
                CURLOPT_HEADER => false,  // don't return headers
                CURLOPT_FOLLOWLOCATION => true,   // follow redirects
                CURLOPT_MAXREDIRS => 10,     // stop after 10 redirects
                CURLOPT_ENCODING => "",     // handle compressed
                CURLOPT_USERAGENT => "test", // name of client
                CURLOPT_AUTOREFERER => true,   // set referrer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
                CURLOPT_TIMEOUT => 120,    // time-out on response
            );

            try {
                $ch = curl_init($url);
                curl_setopt_array($ch, $options);
                $content = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $e) {
            }

            return $content;
        }else{
            return null;
        }
    }

    /**
     * Validate Variable
     * @param $variable
     * @return bool
     */
    public static function validateVariable($variable)
    {
        if(is_string($variable)) $variable = trim($variable);
        return isset($variable) && !is_null($variable) && !empty($variable);
    }
}

