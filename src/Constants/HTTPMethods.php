<?php

namespace VIITech\Helpers\Constants;

use BenSampo\Enum\Enum;

abstract class HTTPMethods extends Enum
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';
}