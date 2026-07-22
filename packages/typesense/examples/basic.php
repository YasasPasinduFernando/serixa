<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Typesense\FakeTypesenseClient;
use Serixa\Typesense\TypesenseEngine;

$engine = TypesenseEngine::make(new FakeTypesenseClient());
$engine->index('books', '1', ['title' => 'Typesense demo']);
$result = $engine->search('books', 'demo');
echo($result->count() === 1 ? "SMOKE OK\n" : "SMOKE FAIL\n");
exit($result->count() === 1 ? 0 : 1);
