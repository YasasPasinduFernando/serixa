<?php

declare(strict_types=1);

namespace Serixa\DevTools\Runtime;

use Serixa\Document\Asset\AssetManager;

/**
 * Progressive-enhancement DevTools client runtime registration helpers.
 */
final class DevToolsRuntime
{
    public const SCRIPT_RELATIVE = 'assets/devtools.js';

    public const CSS_RELATIVE = 'assets/devtools.css';

    /**
     * Absolute filesystem path to the shipped DevTools script.
     */
    public static function scriptPath(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, self::SCRIPT_RELATIVE);
    }

    /**
     * Absolute filesystem path to the shipped DevTools stylesheet.
     */
    public static function cssPath(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, self::CSS_RELATIVE);
    }

    /**
     * Register DevTools JS/CSS on a document asset manager.
     *
     * @param array{js?: string, css?: string} $urls
     */
    public static function register(AssetManager $assets, array $urls = []): AssetManager
    {
        $jsUrl = $urls['js'] ?? '/assets/devtools/devtools.js';
        $cssUrl = $urls['css'] ?? '/assets/devtools/devtools.css';

        $assets->css($cssUrl);
        $assets->js($jsUrl, ['defer' => true]);

        return $assets;
    }
}
