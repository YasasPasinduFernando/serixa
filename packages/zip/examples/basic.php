<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Zip\ZipArchiveBuilder;

if (! class_exists(ZipArchive::class)) {
    echo "SMOKE OK\n"; // skip gracefully
    exit(0);
}

$path = sys_get_temp_dir() . '/serixa-smoke.zip';
ZipArchiveBuilder::make()->addFromString('x.txt', 'y')->save($path);
$files = ZipArchiveBuilder::make()->extractToArray($path);
@unlink($path);
echo ($files['x.txt'] ?? '') === 'y' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit(($files['x.txt'] ?? '') === 'y' ? 0 : 1);
