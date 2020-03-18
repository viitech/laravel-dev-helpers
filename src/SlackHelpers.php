<?php

namespace VIITech\Helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use VIITech\Helpers\Constants\EnvVariables;

/**
 * Class SlackHelpers
 * @package VIITech\Helpers
 */
class SlackHelpers
{
    /**
     * Send Slack with Details
     * @param string $message Message
     * @param string $slack_webhook Slack Incoming Webhook
     * @param string $channel
     * @return Exception|GuzzleException|mixed|ResponseInterface
     */
    public static function sendSlackMessage($message, $slack_webhook = null, $channel = null)
    {
        if(env(EnvVariables::SLACK_MESSAGES_ENABLED, true)){
            if(is_null($slack_webhook)){
                $slack_webhook = env(EnvVariables::SLACK_WEBHOOK_URL, null);
            }
            try {
                $client = new Client(env(EnvVariables::SSL_CACERT) ? ['verify' => env(EnvVariables::SSL_CACERT)] : []);
                return $client->send(new Request('POST', $slack_webhook, [], json_encode(["text" => $message, "channel" =>  $channel])));
            } catch (GuzzleException $e) {
                return $e;
            } catch (Exception $e) {
                return $e;
            }
        }
        return null;
    }

    /**
     * Send Slack with Details
     * @param bool $is_success Is successful or not (Green or red bar)
     * @param string $title Title
     * @param string $message Message
     * @param string $pretext Pretext
     * @param string $username
     * @param string $icon_url
     * @param string $icon_emoji
     * @param string $slack_webhook Slack Incoming Webhook
     * @param string $channel
     * @return Exception|GuzzleException|mixed|ResponseInterface
     */
    public static function sendSlackWithDetails($is_success, $title, $message, $pretext, $username = null, $icon_url = null, $icon_emoji = null, $slack_webhook = null, $channel = null)
    {
        if(env(EnvVariables::SLACK_MESSAGES_ENABLED, true)){
            if(is_null($slack_webhook)){
                $slack_webhook = env(EnvVariables::SLACK_WEBHOOK_URL, null);
            }
            try {
                $color = $is_success ? "#3aa648" : "#ce2101";
                $client = new Client(env(EnvVariables::SSL_CACERT) ? ['verify' => env(EnvVariables::SSL_CACERT)] : []);
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
                    "channel" =>  $channel
                ])));
            } catch (GuzzleException $e) {
                return $e;
            } catch (Exception $e) {
                return $e;
            }
        }
        return null;
    }
}