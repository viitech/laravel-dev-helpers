<?php

namespace VIITech\Helpers;

use Exception;
use Google_Client;
use GuzzleHttp\Client;

class GoogleHelpers
{

    /**
     * Validate Google reCaptcha
     * @param string $google_recaptcha_secret
     * @param string $g_recaptcha_reponse
     * @return boolean
     */
    public static function validateRecaptcha($google_recaptcha_secret, $g_recaptcha_reponse)
    {
        try {
            $client = new Client();
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify', ['form_params'=>
                    [
                        'secret' => $google_recaptcha_secret,
                        'response' => $g_recaptcha_reponse
                    ]
                ]
            );
            $body = json_decode((string) $response->getBody());
            return $body->success;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validate Google Token
     * @param string $google_client_id
     * @param string $token
     * @return boolean
     */
    public static function validateGoogleToken($google_client_id, $token)
    {
        try {
            /** @var Google_Client $client */
            $client = new Google_Client(['client_id' => $google_client_id]);
            $payload = $client->verifyIdToken($token);
            return $payload ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }
}