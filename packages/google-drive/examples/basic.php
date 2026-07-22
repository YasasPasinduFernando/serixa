<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\GoogleDrive\FakeGoogleDriveClient;
use Serixa\GoogleDrive\GoogleDriveStorage;

$s = GoogleDriveStorage::make(new FakeGoogleDriveClient());
$s->put('a.txt', 'gdrive');
echo $s->get('a.txt'), PHP_EOL;
echo $s->exists('a.txt') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($s->exists('a.txt') ? 0 : 1);
