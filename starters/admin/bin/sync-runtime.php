<?php

declare(strict_types=1);

/**
 * Copies the SERIXA client runtime script from the framework package into
 * public/assets/runtime/serixa.js, so the browser can request it directly.
 *
 * Run this whenever you update the serixa/framework dependency:
 *
 *     php bin/sync-runtime.php
 */

use Serixa\Runtime\ClientRuntime;

require dirname(__DIR__) . '/vendor/autoload.php';

$source = ClientRuntime::scriptPath();
$destination = dirname(__DIR__) . '/public/assets/runtime/serixa.js';

if (! is_file($source)) {
    fwrite(STDERR, sprintf('Runtime script not found at %s%s', $source, PHP_EOL));
    exit(1);
}

$destinationDir = dirname($destination);

if (! is_dir($destinationDir) && ! mkdir($destinationDir, 0775, true) && ! is_dir($destinationDir)) {
    fwrite(STDERR, sprintf('Could not create directory %s%s', $destinationDir, PHP_EOL));
    exit(1);
}

if (copy($source, $destination) === false) {
    fwrite(STDERR, sprintf('Failed to copy runtime script to %s%s', $destination, PHP_EOL));
    exit(1);
}

echo sprintf('Copied %s -> %s%s', $source, $destination, PHP_EOL);
