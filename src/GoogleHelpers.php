<?php

namespace VIITech\Helpers;

use Exception;
use GuzzleHttp\Client;

class GoogleHelpers
{

    /**
     * Validate Google reCaptcha
     * @param string $google_recaptcha_secret
     * @param string $g_recaptcha_reponse
     * @return boolean|Exception
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
            return $e;
        }
    }
}