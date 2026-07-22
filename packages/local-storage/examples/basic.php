<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\LocalStorage\MemoryStorage;

$s = new MemoryStorage();
$s->put('hello.txt', 'world');

echo $s->get('hello.txt'), PHP_EOL;
echo $s->url('hello.txt'), PHP_EOL;

if ($s->exists('hello.txt') && $s->get('hello.txt') === 'world') {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
