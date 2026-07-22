<?php

declare(strict_types=1);

namespace Serixa\Algolia;

use Serixa\Algolia\Contracts\AlgoliaClient;
use Serixa\Meilisearch\MemorySearchEngine;

final class FakeAlgoliaClient implements AlgoliaClient
{
    private MemorySearchEngine $engine;

    public function __construct(?MemorySearchEngine $engine = null)
    {
        $this->engine = $engine ?? new MemorySearchEngine();
    }

    public function saveObject(string $index, array $object): void
    {
        $id = (string) ($object['objectID'] ?? $object['id'] ?? '');
        $this->engine->index($index, $id, $object);
    }

    public function deleteObject(string $index, string $objectId): void
    {
        $this->engine->delete($index, $objectId);
    }

    public function search(string $index, string $query, array $options = []): array
    {
        $limit = (int) ($options['hitsPerPage'] ?? $options['limit'] ?? 20);
        $result = $this->engine->search($index, $query, ['limit' => $limit]);

        return [
            'hits' => $result->hits,
            'nbHits' => $result->estimatedTotalHits,
        ];
    }

    public function clearIndex(string $index): void
    {
        $this->engine->clear($index);
    }
}
