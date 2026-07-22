<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\LocalStorage\MemoryStorage;
use Serixa\MediaLibrary\MediaLibrary;

$lib = MediaLibrary::memory(new MemoryStorage());
$item = $lib->add('a.txt', 'hello', 'text/plain');
echo $lib->contents($item->id()), PHP_EOL;
echo count($lib->catalog()->all()) === 1 ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit(count($lib->catalog()->all()) === 1 ? 0 : 1);
