<?php

declare(strict_types=1);

namespace Serixa\Cli\Support;

use Composer\InstalledVersions;
use ReflectionClass;
use RuntimeException;
use Serixa\Runtime\ClientRuntime;

/**
 * Resolves the installed serixa/framework package root.
 *
 * @internal
 */
final class FrameworkLocator
{
    public static function root(): string
    {
        if (class_exists(InstalledVersions::class) && InstalledVersions::isInstalled('serixa/framework')) {
            $path = InstalledVersions::getInstallPath('serixa/framework');

            if (is_string($path) && $path !== '' && is_dir($path)) {
                return rtrim(str_replace('\\', '/', $path), '/');
            }
        }

        $file = (new ReflectionClass(ClientRuntime::class))->getFileName();

        if (! is_string($file) || $file === '') {
            throw new RuntimeException('Unable to locate serixa/framework. Require serixa/framework via Composer.');
        }

        // …/framework/src/Runtime/ClientRuntime.php → framework/
        return dirname($file, 3);
    }
}
