<?php

namespace VIITech\Helpers\Constants;

use BenSampo\Enum\Enum;

/**
 * Class DebuggerLevels
 * @package VIITech\Helpers\Constants
 */
class DebuggerLevels extends Enum
{
    const INFO = "info";
    const ERROR = "error";
    const WARNING = "warning";
    const MESSAGE = "message";
    const ALERT = "alert";
}