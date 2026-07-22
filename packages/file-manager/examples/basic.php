<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\FileManager\FileManager;
use Serixa\LocalStorage\MemoryStorage;

$fm = FileManager::make(new MemoryStorage());
$fm->write('hello.txt', 'world');
echo $fm->read('hello.txt'), PHP_EOL;
echo $fm->exists('hello.txt') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($fm->exists('hello.txt') ? 0 : 1);
