<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Dropbox\DropboxStorage;
use Serixa\Dropbox\FakeDropboxClient;

$s = DropboxStorage::make(new FakeDropboxClient());
$s->put('z.txt', 'dropbox');
echo $s->get('z.txt'), PHP_EOL;
echo $s->exists('z.txt') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($s->exists('z.txt') ? 0 : 1);
