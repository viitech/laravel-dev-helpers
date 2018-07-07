<?php

namespace VIITech\Helpers;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

class SlackHelpers
{
    /**
     * Send Slack with Details
     * @param string $slack_webhook Slack Incoming Webhook
     * @param string $message Message
     * @return Exception|GuzzleException|mixed|\Psr\Http\Message\ResponseInterface
     */
    public static function sendSlackMessage($slack_webhook, $message)
    {
        try {
            $client = new \GuzzleHttp\Client(env("SSL_CACERT") ? ['verify' => env("SSL_CACERT")] : []);
            return $client->send(new Request('POST', $slack_webhook, [], json_encode(["text" => $message])));
        } catch (GuzzleException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Send Slack with Details
     * @param string $slack_webhook Slack Incoming Webhook
     * @param bool $is_success Is successful or not (Green or red bar)
     * @param string $title Title
     * @param string $message Message
     * @param string $pretext Pretext
     * @param string $username
     * @param string $icon_url
     * @param string $icon_emoji
     * @return Exception|GuzzleException|mixed|\Psr\Http\Message\ResponseInterface
     */
    public static function sendSlackWithDetails($slack_webhook, $is_success, $title, $message, $pretext, $username = null, $icon_url = null, $icon_emoji = null)
    {
        try {
            $color = $is_success ? "#3aa648" : "#ce2101";
            $client = new \GuzzleHttp\Client(env("SSL_CACERT") ? ['verify' => env("SSL_CACERT")] : []);
            return $client->send(new Request('POST', $slack_webhook, [], json_encode([
                "attachments" => [
                    [
                        "fallback" => $pretext,
                        "pretext" => $pretext,
                        "color" => $color,
                        "fields" => [
                            [
                                "title" => $title,
                                "value" => $message,
                                "short" => false
                            ]
                        ]
                    ]
                ],
                "username" => $username,
                "icon_url" => $icon_url,
                "icon_emoji" => $icon_emoji,
            ])));
        } catch (GuzzleException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }
}