<?php

namespace App\Constants;

use BenSampo\Enum\Enum;

abstract class CastingTypes extends Enum
{
    const STRING = "string";
    const INTEGER = "integer";
    const BOOLEAN = "boolean";
    const ARRAY = "array";
}