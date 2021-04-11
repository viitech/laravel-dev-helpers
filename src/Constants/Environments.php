<?php

namespace VIITech\Helpers\Constants;

use BenSampo\Enum\Enum;

/**
 * Class Environments
 * @package VIITech\Helpers\Constants
 */
class Environments extends Enum
{
    const LOCAL = "local";
    const DEVELOPMENT = "dev";
    const TESTING = "testing";
    const STAGING = "staging";
    const BETA = "beta";
    const PRODUCTION = "production";
}