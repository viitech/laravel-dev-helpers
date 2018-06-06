<?php

namespace VIITech\Helpers;

use GuzzleHttp\Exception\GuzzleException, Log;

class FirebaseHelpers
{
    /**
     * Generate Dynamic Link
     * @param string $api_key Firebase Web API Key
     * @param string $longDynamicLink
     * @param string $suffix_option UNGUESSABLE or SHORT
     * @return string|null
     */
    public static function generateDynamicLink($api_key, $longDynamicLink, $suffix_option = "UNGUESSABLE")
    {
        try {
            $guzzle_request = (new \GuzzleHttp\Client())->request('POST', 'https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=' . $api_key, [
                'json' => [
                    "longDynamicLink" => $longDynamicLink,
                    "suffix" => [
                        "option" => $suffix_option
                    ]
                ]
            ]);
            if($guzzle_request->getStatusCode() == 200){
                $result = json_decode($guzzle_request->getBody()->getContents());
                return $result->shortLink;
            }else{
                return null;
            }
        } catch (GuzzleException $e) {
            return null;
        }
    }

    /**
     * Send Firebase Cloud Messaging
     * @param $data_array
     * @param bool $is_logging_enabled
     */
    public static function sendFCM($data_array, $is_logging_enabled = false){
        try {
            $guzzle_request = (new \GuzzleHttp\Client())->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=' . env('FCM_KEY')
                ],
                'body' => json_encode($data_array)
            ]);
            if($is_logging_enabled){
                Log::info("sendFCM result: " . $guzzle_request->getBody()->getContents());
            }
        } catch (GuzzleException $e) {
            if($is_logging_enabled){
                Log::error($e);
            }
        }
    }
}