<?php

namespace VIITech\Helpers;

use Exception;
use Google_Client;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use VIITech\Helpers\Constants\DebuggerLevels;
use VIITech\Helpers\Constants\EnvVariables;

class MailchimpHelpers
{

    /**
     * Subscribe Email
     * @param string $url
     * @param string $email
     * @return Exception|mixed|ResponseInterface|GuzzleException
     */
    public function subscribeEmail($url, $email)
    {
        try {
            $client = new Client(env(EnvVariables::SSL_CACERT) ? ['verify' => env(EnvVariables::SSL_CACERT)] : []);
            $result = $client->send(new Request('POST', $url, [], json_encode([
                "email" => $email
            ])));
            return $result;
        } catch (GuzzleException $e) {
            GlobalHelpers::debugger($e, DebuggerLevels::ERROR);
            return $e;
        } catch (Exception $e) {
            GlobalHelpers::debugger($e, DebuggerLevels::ERROR);
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