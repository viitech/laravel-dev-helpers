<?php

namespace VIITech\Helpers;

use Exception;
use GuzzleHttp\Psr7\Request;

class SlackHelpers
{
    /**
     * Send Slack with Details
     * @param string $slack_webhook Slack Incoming Webhook
     * @param string $message Message
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendSlackMessage($slack_webhook, $message)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $body = "payload={\"text\": \"$message\"}";
            $client->send(new Request('POST', $slack_webhook, [], $body));
        } catch (Exception $e) {
        }
    }

    /**
     * Send Slack with Details
     * @param string $slack_webhook Slack Incoming Webhook
     * @param bool $is_success Is successful or not (Green or red bar)
     * @param string $title Title
     * @param string $message Message
     * @param string $pretext Pretext
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendSlackWithDetails($slack_webhook, $is_success, $title, $message, $pretext)
    {
        try {
            $color = $is_success ? "#3aa648" : "#ce2101";
            $client = new \GuzzleHttp\Client();
            $body = "{
       \"attachments\":[
          {
             \"fallback\":\"$pretext\",
             \"pretext\":\"$pretext\",
             \"color\":\"$color\",
             \"fields\":[
                {
                   \"title\":\"$title\",
                   \"value\":\"$message\",
                   \"short\":false
                }
             ]
          }
       ]
    }";
            $client->send(new Request('POST', $slack_webhook, [], $body));
        } catch (Exception $e) {
        }
    }
}