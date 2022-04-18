<?php

namespace VIITech\Helpers\Constants;

use BenSampo\Enum\Enum;

/**
 * Class EnvVariables
 * @package VIITech\Helpers\Constants
 */
class EnvVariables extends Enum
{
    const APP_TIMEZONE = "APP_TIMEZONE";
    const DEBUGGER_LOGS_ENABLED = "DEBUGGER_LOGS_ENABLED";
    const SENTRY_ENABLED = "SENTRY_ENABLED";
    const FCM_KEY = "FCM_KEY";
    const SSL_CACERT = "SSL_CACERT";
    const CORS_ALLOW_ORIGIN = "CORS_ALLOW_ORIGIN";
    const FORCE_HTTPS = "FORCE_HTTPS";
    const ENABLE_JWT = "ENABLE_JWT";
    const API_CUSTOM_SERIALIZER = "API_CUSTOM_SERIALIZER";
    const SLACK_MESSAGES_ENABLED = "SLACK_MESSAGES_ENABLED";
    const SLACK_WEBHOOK_URL = "SLACK_WEBHOOK_URL";
    const QUEUE_DRIVER = "QUEUE_DRIVER";
    const GOOGLE_CALENDAR_API = "GOOGLE_CALENDAR_API";
    const APP_STAGE = "APP_STAGE";
    const APP_ENV = "APP_ENV";
    const NEW_YEAR = "NEW_YEAR";
}