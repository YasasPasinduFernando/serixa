<?php

declare(strict_types=1);

use Serixa\Runtime\ClientRuntime;

require dirname(__DIR__) . '/vendor/autoload.php';

$source = ClientRuntime::scriptPath();
$destination = dirname(__DIR__) . '/public/assets/runtime/serixa.js';

if (! is_file($source)) {
    fwrite(STDERR, sprintf("Runtime script not found at %s\n", $source));
    exit(1);
}

$destinationDir = dirname($destination);

if (! is_dir($destinationDir) && ! mkdir($destinationDir, 0775, true) && ! is_dir($destinationDir)) {
    fwrite(STDERR, sprintf("Could not create directory %s\n", $destinationDir));
    exit(1);
}

if (copy($source, $destination) === false) {
    fwrite(STDERR, sprintf("Failed to copy runtime script to %s\n", $destination));
    exit(1);
}

echo sprintf("Copied %s -> %s\n", $source, $destination);
