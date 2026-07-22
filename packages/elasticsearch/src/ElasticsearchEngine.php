<?php

declare(strict_types=1);

namespace Serixa\Elasticsearch;

use Serixa\Elasticsearch\Contracts\ElasticsearchClient;
use Serixa\Meilisearch\Contracts\SearchEngine;
use Serixa\Meilisearch\Exceptions\SearchException;
use Serixa\Meilisearch\SearchResult;

final class ElasticsearchEngine implements SearchEngine
{
    public function __construct(
        private readonly ElasticsearchClient $client,
    ) {
    }

    public static function make(ElasticsearchClient $client): self
    {
        return new self($client);
    }

    public function index(string $index, string $id, array $document): void
    {
        $id = trim($id);
        if ($id === '') {
            throw SearchException::invalidDocumentId($id);
        }
        $document['id'] = $id;
        $this->client->indexDocument($index, $id, $document);
    }

    public function delete(string $index, string $id): void
    {
        $this->client->deleteDocument($index, trim($id));
    }

    public function search(string $index, string $query, array $options = []): SearchResult
    {
        $payload = $this->client->search($index, $query, $options);
        $hits = [];
        foreach ($payload['hits']['hits'] ?? [] as $hit) {
            if (is_array($hit) && isset($hit['_source']) && is_array($hit['_source'])) {
                /** @var array<string, mixed> $source */
                $source = $hit['_source'];
                $hits[] = $source;
            }
        }
        $total = (int) ($payload['hits']['total']['value'] ?? count($hits));

        return new SearchResult($query, $hits, $total);
    }

    public function clear(string $index): void
    {
        $this->client->deleteIndex($index);
    }
}
