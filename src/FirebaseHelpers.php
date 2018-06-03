<?php

namespace VIITech\Helpers;

use GuzzleHttp\Exception\GuzzleException;

class FirebaseHelpers
{
    /**
     * Generate Dynamic Link
     * @param string $api_key Firebase Web API Key
     * @param string $longDynamicLink
     * @param string $suffix_option UNGUESSABLE or SHORT
     * @return string|null
     */
    function generateDynamicLink($api_key, $longDynamicLink, $suffix_option = "UNGUESSABLE")
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
}