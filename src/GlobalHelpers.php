<?php

namespace VIITech;

namespace VIITech\Helpers;

class GlobalHelpers
{

    function isDevelopmentEnv()
    {
        return app()->environment() === 'local' || app()->environment() === 'dev';
    }

}

