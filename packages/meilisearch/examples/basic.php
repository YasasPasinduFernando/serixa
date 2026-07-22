<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Meilisearch\FakeMeilisearchClient;
use Serixa\Meilisearch\MeilisearchEngine;

$engine = MeilisearchEngine::make(new FakeMeilisearchClient());
$engine->index('docs', '1', ['title' => 'Getting started with Serixa']);
$result = $engine->search('docs', 'serixa');
echo $result->hits[0]['title'] ?? 'missing', PHP_EOL;
echo $result->count() === 1 ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->count() === 1 ? 0 : 1);
