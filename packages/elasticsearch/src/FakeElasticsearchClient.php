<?php

declare(strict_types=1);

namespace Serixa\Elasticsearch;

use Serixa\Elasticsearch\Contracts\ElasticsearchClient;
use Serixa\Meilisearch\MemorySearchEngine;

final class FakeElasticsearchClient implements ElasticsearchClient
{
    private MemorySearchEngine $engine;

    public function __construct(?MemorySearchEngine $engine = null)
    {
        $this->engine = $engine ?? new MemorySearchEngine();
    }

    public function indexDocument(string $index, string $id, array $document): void
    {
        $this->engine->index($index, $id, $document);
    }

    public function deleteDocument(string $index, string $id): void
    {
        $this->engine->delete($index, $id);
    }

    public function search(string $index, string $query, array $options = []): array
    {
        $limit = (int) ($options['size'] ?? $options['limit'] ?? 20);
        $result = $this->engine->search($index, $query, ['limit' => $limit]);
        $hits = [];
        foreach ($result->hits as $document) {
            $hits[] = ['_source' => $document];
        }

        return [
            'hits' => [
                'hits' => $hits,
                'total' => ['value' => $result->estimatedTotalHits],
            ],
        ];
    }

    public function deleteIndex(string $index): void
    {
        $this->engine->clear($index);
    }
}
