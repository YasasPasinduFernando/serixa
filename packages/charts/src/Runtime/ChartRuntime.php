<?php

declare(strict_types=1);

namespace Serixa\Charts\Runtime;

use Serixa\Document\Asset\AssetManager;

/**
 * Progressive-enhancement charts client runtime registration helpers.
 *
 * Canonical script: assets/charts.js (shipped with serixa/charts).
 * Apps must include serixa.js (framework) and charts.js so the `chart` module
 * is registered before Serixa.boot() mounts [data-serixa-chart] elements.
 */
final class ChartRuntime
{
    public const SCRIPT_RELATIVE = 'assets/charts.js';

    /**
     * Absolute filesystem path to the shipped charts script.
     */
    public static function scriptPath(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, self::SCRIPT_RELATIVE);
    }

    /**
     * Register the deferred charts script on a document asset manager.
     *
     * @param string $publicUrl URL the browser will request (host app must serve the file).
     */
    public static function register(AssetManager $assets, string $publicUrl = '/assets/charts/charts.js'): AssetManager
    {
        return $assets->js($publicUrl, ['defer' => true]);
    }
}
