<?php

namespace VIITech\Helpers\Constants;

use BenSampo\Enum\Enum;

abstract class EnvVariables extends Enum
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
}