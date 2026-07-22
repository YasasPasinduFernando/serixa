<?php

declare(strict_types=1);

/**
 * Offline asset build for the documentation website.
 *
 *     php bin/build-assets.php
 */

use Serixa\Styling\StyleSheet;
use Serixa\Runtime\ClientRuntime;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);

try {
    $css = StyleSheet::build($root);
} catch (Throwable $e) {
    fwrite(STDERR, 'CSS build failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$runtimeSrc = ClientRuntime::scriptPath();
$runtimeDest = $root . '/public/assets/runtime/serixa.js';
if (! is_dir(dirname($runtimeDest))) {
    mkdir(dirname($runtimeDest), 0775, true);
}
copy($runtimeSrc, $runtimeDest);

echo sprintf(
    "Built %s (%d bytes, %d rules) from [%s]%s",
    'public/assets/serixa.css',
    $css['bytes'],
    $css['rules'] ?? 0,
    implode(', ', $css['sources']),
    PHP_EOL,
);
echo "Copied runtime → public/assets/runtime/serixa.js" . PHP_EOL;
