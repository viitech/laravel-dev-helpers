<?php

namespace App\Constants;

use BenSampo\Enum\Enum;

abstract class EnvVariables extends Enum
{
    const APP_TIMEZONE = "APP_TIMEZONE";
    const DEBUGGER_LOGS_ENABLED = "DEBUGGER_LOGS_ENABLED";
}