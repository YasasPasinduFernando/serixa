<?php

declare(strict_types=1);

use Serixa\DevTools\DevTools;

if (! function_exists('serixa_devtools_enabled')) {
    function serixa_devtools_enabled(): bool
    {
        return DevTools::enabled();
    }
}
