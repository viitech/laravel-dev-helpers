<?php

namespace VIITech\Helpers;

use Exception;
use Google_Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class MailchimpHelpers
{

    /**
     * Subscribe Email
     * @param $email
     * @return Exception|mixed|\Psr\Http\Message\ResponseInterface|GuzzleException
     */
    public function subscribeEmail($url, $email)
    {
        $url = 'http://myspringring.us16.list-manage.com/subscribe/post?u=085552a2ec875bed2deb17302&id=4b8d90b2a1';
        try {
            $client = new \GuzzleHttp\Client(env("SSL_CACERT") ? ['verify' => env("SSL_CACERT")] : []);
            $result = $client->send(new \GuzzleHttp\Psr7\Request('POST', $url, [], json_encode([
                "email" => $email
            ])));
            return $result;
        } catch (GuzzleException $e) {
            Log::error($e);
            return $e;
        } catch (Exception $e) {
            Log::error($e);
            return $e;
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