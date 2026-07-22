<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Elasticsearch\ElasticsearchEngine;
use Serixa\Elasticsearch\FakeElasticsearchClient;

$engine = ElasticsearchEngine::make(new FakeElasticsearchClient());
$engine->index('logs', '1', ['message' => 'Elasticsearch demo']);
$result = $engine->search('logs', 'demo');
echo($result->count() === 1 ? "SMOKE OK\n" : "SMOKE FAIL\n");
exit($result->count() === 1 ? 0 : 1);
