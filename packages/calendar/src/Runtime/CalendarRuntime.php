<?php

declare(strict_types=1);

namespace Serixa\Calendar\Runtime;

use Serixa\Document\Asset\AssetManager;

/**
 * Progressive-enhancement calendar client runtime helpers.
 */
final class CalendarRuntime
{
    public const SCRIPT_RELATIVE = 'assets/calendar.js';

    public static function scriptPath(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, self::SCRIPT_RELATIVE);
    }

    public static function register(AssetManager $assets, string $publicUrl = '/assets/calendar/calendar.js'): AssetManager
    {
        return $assets->js($publicUrl, ['defer' => true]);
    }
}
