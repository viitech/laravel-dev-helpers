<?php

namespace VIITech\Helpers\Constants;

use BenSampo\Enum\Enum;

abstract class Environments extends Enum
{
    const LOCAL = "local";
    const DEVELOPMENT = "dev";
    const STAGING = "staging";
    const BETA = "beta";
    const PRODUCTION = "production";
}