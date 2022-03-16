<?php

namespace VIITech\Helpers;

use Carbon\Carbon;
use Exception;
use Google_Client;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use VIITech\Helpers\Constants\Attributes;
use VIITech\Helpers\Constants\EnvVariables;
use VIITech\Helpers\Constants\Values;

/**
 * Google Helpers
 */
class GoogleHelpers
{

    /**
     * Validate Google reCaptcha
     * @param string $google_recaptcha_secret
     * @param string $g_recaptcha_response
     * @return boolean
     */
    public static function validateRecaptcha($google_recaptcha_secret, $g_recaptcha_response)
    {
        try {
            $client = new Client();
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify', ['form_params'=>
                    [
                        'secret' => $google_recaptcha_secret,
                        'response' => $g_recaptcha_response
                    ]
                ]
            );
            return json_decode((string) $response->getBody())->success;
        } catch (Exception | GuzzleException $e) {
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
            $client = new Google_Client([Attributes::CLIENT_ID => $google_client_id]);
            $payload = $client->verifyIdToken($token);
            return (bool) $payload;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Parse Google Calendar
     * @param $url
     * @param null $calendar_id
     * @param null $timeMin
     * @param null $timeMax
     * @param null $updatedMin
     * @param bool $debug
     * @return array|false
     */
    static function parseGoogleCalendar($url, $calendar_id = null, $timeMin = null, $timeMax = null, $updatedMin = null, $debug = false){

        if(is_null($calendar_id)){
            $calendar_id = self::validateGoogleCalendarLink($url);
        }

        if(!$calendar_id){
            return false;
        }

        if(is_null($timeMin)){
            $timeMin = GlobalHelpers::now(null, null, 0, -6);
        }

        if(is_null($timeMax)){
            $timeMax = GlobalHelpers::now(null, null, 0, 18);
        }

        if(!is_null($updatedMin)){
            $updatedMin = "&updatedMin=$updatedMin";
        }

        $json_response = null;
        $calendar_events_array = [];
        $deleted_events_array = collect();

        try {
            $client = new Client(['base_uri' => 'https://www.googleapis.com',]);
            $google_api_key = env(EnvVariables::GOOGLE_CALENDAR_API);
            $singleEvents = "true";
            $showDeleted = "true";
            if($debug){
                dd("https://www.googleapis.com/calendar/v3/calendars/" . urldecode($calendar_id) . "/events?singleEvents=$singleEvents&showDeleted=$showDeleted&orderBy=startTime&timeMin=$timeMin&timeMax=$timeMax$updatedMin&key=$google_api_key");
            }
            $response = $client->get("calendar/v3/calendars/" . urldecode($calendar_id) . "/events?singleEvents=$singleEvents&showDeleted=$showDeleted&orderBy=startTime&timeMin=$timeMin&timeMax=$timeMax$updatedMin&key=$google_api_key");
            $response_body = (string)$response->getBody();
            $json_response = json_decode($response_body);
        } catch (Exception | GuzzleException $e) {
            SlackHelpers::sendSlackMessage($e->getMessage());
        }

        if(GlobalHelpers::isValidObject($json_response) && GlobalHelpers::isValidObject($json_response->items)) {

            $calendar_timezone = $json_response->timeZone;
            if(!GlobalHelpers::isValidVariable($calendar_timezone)){
                $calendar_timezone = Values::DEFAULT_TIMEZONE;
            }

            foreach ($json_response->items as $event) {

                // delete the event
                if(isset($event->status) && $event->status == "cancelled"){
                    $deleted_events_array->add($event->id);
                    continue;
                }

                if(!isset($event->summary)){
                    continue;
                }

                $name = $event->summary;
                $all_day = false;

                $start_date = null;
                $end_date = null;

                if(isset($event->start->dateTime)) {
                    $timezone = $event->end->timeZone ?? $calendar_timezone;
                    $start_date = Carbon::parse($event->start->dateTime, $timezone);
                }else if(isset($event->start->date)) {
                    $start_date = Carbon::parse($event->start->date, $calendar_timezone);
                }

                if(isset($event->end->dateTime)) {
                    $timezone = $event->end->timeZone ?? $calendar_timezone;
                    $end_date = Carbon::parse($event->end->dateTime, $timezone);
                }else if(isset($event->end->date)) {
                    // Reason of that that Google API returns the next day as if the day is 24 hours not 23:59:59. It causes incorrect data in the app
                    $end_date = Carbon::parse($event->end->date, $calendar_timezone)->subSecond()->hour(0)->minute(0)->second(0);
                }

                if(is_null($start_date)){
                    continue;
                }else if(is_null($end_date)){
                    continue;
                }

                // review this logic
                if(isset($event->start->date) && isset($event->end->date)){
                    $all_day = true;
                }

                if($all_day){
                    $start_date = $start_date->startOfDay();
                    $end_date = $end_date->endOfDay();
                }

                $start_date_formatted = $start_date->format("c");
                $end_date_formatted = $end_date->format("c");

                $location = $event->location ?? null;
                $description = $event->description ?? null;

                $calendar_events_array[] = [
                    Attributes::START_DATE => $start_date_formatted,
                    Attributes::END_DATE => $end_date_formatted,
                    Attributes::ALL_DAY => $all_day,
                    Attributes::GOOGLE_EVENT_ID => $event->id,
                    Attributes::DESCRIPTION => $description,
                    Attributes::LOCATION => $location,
                    Attributes::TITLE => $name,
                ];
            }

        }

        return [
            Attributes::GOOGLE_EVENTS => $calendar_events_array,
            Attributes::DELETED_EVENTS => $deleted_events_array->values()->unique()->toArray(),
            Attributes::TIMEZONE => $calendar_timezone ?? Values::DEFAULT_TIMEZONE
        ];

    }

    /**
     * Validate Google Calendar Link
     * @param $url
     * @return string
     */
    static function validateGoogleCalendarLink($url){

        if(is_null($url)){
            return false;
        }

        if(!Str::startsWith($url, "https://calendar.google.com")){
            return false;
        }

        $calendar_id = str_replace("https://calendar.google.com/calendar/ical/", "", $url);
        $calendar_id = str_replace("/public/basic.ics", "", $calendar_id);
        $calendar_id = str_replace("https://calendar.google.com/calendar/u/0/embed?src=", "", $calendar_id);
        $calendar_id = str_replace("https://calendar.google.com/calendar/embed?src=", "", $calendar_id);

        if(Str::startsWith($calendar_id, "http")){
            return false;
        }

        if(Str::contains($calendar_id, "&")){
            $calendar_id = substr($calendar_id, 0, strpos($calendar_id, "&"));
        }

        return trim($calendar_id);
    }
}