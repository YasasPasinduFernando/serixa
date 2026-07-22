<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Algolia\AlgoliaEngine;
use Serixa\Algolia\FakeAlgoliaClient;

$engine = AlgoliaEngine::make(new FakeAlgoliaClient());
$engine->index('items', '1', ['title' => 'Algolia demo']);
$result = $engine->search('items', 'demo');
echo($result->count() === 1 ? "SMOKE OK\n" : "SMOKE FAIL\n");
exit($result->count() === 1 ? 0 : 1);
