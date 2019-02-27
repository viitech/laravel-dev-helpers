<?php

namespace VIITech\Helpers;

use VIITech\Helpers\Constants\Headers;
use Dingo\Api\Contract\Http\Request;
use DOMDocument, DOMXPath, Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Process\Process;
use VIITech\Helpers\Constants\Attributes;
use VIITech\Helpers\Constants\CastingTypes;
use VIITech\Helpers\Constants\DebuggerLevels;
use VIITech\Helpers\Constants\Environments;
use VIITech\Helpers\Constants\EnvVariables;
use VIITech\Helpers\Constants\Platforms;
use VIITech\Helpers\Requests\CustomRequest;

class GlobalHelpers
{

    // Git Branches
    const GIT_BRANCH_MASTER = "master";
    const GIT_BRANCH_STAGING = "staging";
    const GIT_BRANCH_BETA = "beta";
    const GIT_BRANCH_DEV = "dev";

    // HTTP Request Methods
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';

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
     * Is Development Environment
     * @return bool
     */
    public static function isDevelopmentEnv()
    {
        return static::checkEnvironment(Environments::LOCAL) || static::checkEnvironment(Environments::DEVELOPMENT);
    }

    /**
     * Is Testing Environment
     * @return bool
     */
    public static function isTestingEnv()
    {
        return static::checkEnvironment(Environments::TESTING);
    }

    /**
     * Get Binary Path
     * @param string $binary_name
     * @return string|null
     */
    public static function getBinaryPath($binary_name)
    {
        try {
            $result = trim(shell_exec('which ' . $binary_name));
            if ($result != null) {
                return $result;
            }
            return env($binary_name,  "/usr/local/bin/" . $binary_name);
        } catch (Exception $e) {
            return env($binary_name, "/usr/local/bin/" . $binary_name);
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
                return $var instanceof $type || is_a($var, $type); // is variable instance of type
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
        if ($val === Attributes::TRUE) {
            $val = true;
        } else if ($val === Attributes::FALSE) {
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
     * Get Value From HTTP Request
     * @param \Illuminate\Http\Request|Request|\Illuminate\Support\Collection|array $request
     * @param string $key
     * @param mixed $default
     * @param mixed $type
     * @return mixed
     */
    public static function getValueFromHTTPRequest($request, $key, $default = null, $type = null)
    {

        $return_value = null;

        if (is_array($request)) {
            $request = collect($request);
        }

        // get value
        if (!is_null($request) && $request->has($key)) {
            $return_value = $request->get($key);
            if (is_null($return_value) && isset($request->all()[$key])) {
                $return_value = $request->all()[$key];
            }
        } else if (is_array($request) && array_key_exists($key, $request)) {
            $return_value = $request[$key];
        } else {
            $return_value = $default;
        }

        // return value
        return GlobalHelpers::getValueAsType($return_value, $type);
    }

    /**
     * Get Value As Type
     * @param $type
     * @param $return_value
     * @return array|bool|int|string
     */
    public static function getValueAsType($return_value, $type = CastingTypes::STRING)
    {
        if ($return_value === Attributes::FALSE) {
            $return_value = false;
        } else if ($return_value === Attributes::TRUE) {
            $return_value = true;
        }

        // validate and cast object
        try {
            if(is_null($return_value)){
                return $return_value;
            } else if ($type == CastingTypes::ARRAY) { // array
                return (array) $return_value;
            } else if ($type == CastingTypes::STRING) { // string
                return (string) $return_value;
            } else if ($type == CastingTypes::BOOLEAN) { // boolean
                return (boolean) $return_value;
            } else if ($type == CastingTypes::INTEGER) { // integer
                return (int) $return_value;
            } else {
                return $return_value;
            }
        } catch (Exception $e) {
            return $return_value;
        }
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
        return $bool == 0 ? "No" : "Yes";
    }

    /**
     * Run Shell Command
     * @param $command
     * @param bool $return_status
     * @return string|boolean
     */
    public static function runShellCommand($command, $return_status = false)
    {
        try {
            $process = new Process($command);
            $process->run();
            if($return_status){
                return $process->isSuccessful();
            }
            if (!$process->isSuccessful()) {
                return $process->getErrorOutput();
            }
            return $process->getOutput();
        } catch (Exception $e) {
            if($return_status){
                return false;
            }
            return $e->getMessage();
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
     * Return Response (returnResponse version 1)
     * @param bool $success
     * @param string $message
     * @param array $data
     * @param array $error
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public static function returnResponse($success = true, $message = '', $data = [], $error = [], $status = Response::HTTP_OK, $headers = [])
    {
        if (is_null($data)) {
            $data = [];
        }
        if (is_null($error)) {
            $error = [];
        }

        return response()->json([
            Attributes::STATUS => $status,
            Attributes::SUCCESS => $success,
            Attributes::MESSAGE => $message,
            Attributes::DATA => $data,
            Attributes::ERROR => $error,
        ], $status, $headers);
    }

    /**
     * Returns Formatted JSON Response (returnResponse version 2)
     * @param string $message
     * @param array $data
     * @param array $error
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public static function formattedJSONResponse($message = '', $data = [], $error = [], $status = Response::HTTP_OK, $headers = [])
    {
        if (is_null($data)) {
            $data = [];
        }
        if (is_null($error)) {
            $error = [];
        }

        return response()->json([
            Attributes::MESSAGE => $message,
            Attributes::DATA => $data,
            Attributes::ERROR => $error,
        ], $status, $headers);
    }

    /**
     * Return JSON Response
     * @param array $array
     * @param int $status
     * @return \Dingo\Api\Http\Response|\Illuminate\Http\JsonResponse
     */
    public static function returnJSONResponse($array, $status = Response::HTTP_OK)
    {
        return response()->json($array, $status);
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
            if ($code == Response::HTTP_OK || $code == Response::HTTP_MOVED_PERMANENTLY || $code == Response::HTTP_FOUND) { // exists
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
     * Check if variable is valid
     * @param $variable
     * @return bool
     */
    public static function isValidVariable($variable)
    {
        if(is_string($variable)) {
            $variable = trim($variable);
            return isset($variable) && !is_null($variable) && !empty($variable);
        }
        return isset($variable) && !is_null($variable);
    }

    /**
     * Debugger
     * @param \Exception|string $e
     * @param string $level
     */
    public static function debugger($e, $level)
    {
        try {
            $debugger_logs_enabled = env(EnvVariables::DEBUGGER_LOGS_ENABLED, false);
            if ($debugger_logs_enabled) {
                if ($level == DebuggerLevels::ERROR) {
                    Log::error($e);
                } else if ($level == DebuggerLevels::WARNING) {
                    Log::warning($e);
                } else if ($level == DebuggerLevels::INFO) {
                    Log::info($e);
                } else if ($level == DebuggerLevels::ALERT) {
                    Log::alert($e);
                }
            }
        } catch (Exception $e) {}
    }

    /**
     * Return value from nullable object
     * @param $data
     * @param $field
     * @return null|string
     */
    public static function returnValueFromNullableObject($data, $field)
    {
        try {
            if (is_null($data)) {
                return null;
            }
            return $data->$field;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Return Integer
     * @param mixed $value
     * @return integer
     */
    public static function returnInteger($value)
    {
        try {
            if (is_null($value)) {
                return intval(0);
            }
            return intval($value);
        } catch (Exception $e) {
            return intval(0);
        }
    }

    /**
     * Format Number
     * @param mixed $number
     * @param int $decimals
     * @param string $decimal_point
     * @param string $thousands_sep
     * @return string|mixed
     */
    public static function formatNumber($number, $decimals = 1, $decimal_point = '.', $thousands_sep = '')
    {
        try {
            return number_format($number, $decimals, $decimal_point, $thousands_sep);
        } catch (Exception $e) {
            try {
                return (string) $number;
            } catch (Exception $e) {
                return $number;
            }
        }
    }

    /**
     * Return Float
     * @param mixed $value
     * @return float
     */
    public static function returnFloat($value)
    {
        try {
            if (is_null($value)) {
                return floatval(0);
            }
            return floatval($value);
        } catch (Exception $e) {
            return floatval(0);
        }
    }

    /**
     * Hash Password (bcrypt)
     * @param $value
     * @return mixed
     */
    public static function hashPassword($value)
    {
        try {
            if (Hash::needsRehash($value)) {
                return Hash::make($value);
            }
            return $value;
        } catch (Exception $e) {
            return $value;
        }
    }

    /**
     * Hex To RGB
     * @param $hex
     * @return array
     */
    public static function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return $rgb; // returns an array with the rgb values
    }

    /**
     * Set Laravel Environment Value
     * @param string $envKey
     * @param string $envValue
     * @return bool|Exception
     */
    public static function setLaravelEnvironmentValue($envKey, $envValue)
    {
        try {
            $envFile = base_path('.env');
            $str = file_get_contents($envFile);
            $oldValue = env($envKey);
            if($oldValue == 0 || $oldValue == 1) $oldValue = ($oldValue) ? Attributes::TRUE : Attributes::FALSE;
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
            $fp = fopen($envFile, 'w');
            fwrite($fp, $str);
            fclose($fp);
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }


    /**
     * Return Boolean As String
     * @param $val
     * @return string
     */
    public static function returnBooleanString($val)
    {
        return $val ? Attributes::TRUE : Attributes::FALSE;
    }

    /**
     * Create API Request Object
     * @param array $data
     * @param bool $dingo_api
     * @return \Dingo\Api\Http\Request|\Illuminate\Http\Request
     */
    public static function createAPIRequestObject($data = [], $dingo_api = true)
    {
        if($dingo_api){
            $request = new \Dingo\Api\Http\Request();
        }else{
            $request = new \Illuminate\Http\Request();
        }
        $request->replace($data);
        return $request;
    }

    /**
     * Is Platform Mobile
     * @param Request|\Illuminate\Http\Request $request
     * @return bool
     */
    public static function isPlatformMobile($request){
        $platform = $request->header(Headers::PLATFORM);
        return $platform == Platforms::IOS || $platform == Platforms::ANDROID || $platform == Platforms::MOBILE;
    }

    /**
     * Validate Requests
     * @param CustomRequest $formRequest
     * @param \Dingo\Api\Http\Request|\Illuminate\Http\Request $request
     * @param int $error_status_code
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public static function validateRequest($formRequest, $request, $error_status_code = Response::HTTP_BAD_REQUEST)
    {
        $validator = Validator::make($request->all(), $formRequest->rules(),  $formRequest->messages(),  $formRequest->attributes());
        if ($validator->fails()) {
            return GlobalHelpers::formattedJSONResponse($validator->errors()->first(), null, $validator->errors(), $error_status_code);
        }
        return true;
    }
}

