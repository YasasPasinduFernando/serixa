<?php

declare(strict_types=1);

namespace Serixa\Meilisearch;

use Serixa\Meilisearch\Contracts\MeilisearchClient;
use Serixa\Meilisearch\Contracts\SearchEngine;
use Serixa\Meilisearch\Exceptions\SearchException;

final class MeilisearchEngine implements SearchEngine
{
    public function __construct(
        private readonly MeilisearchClient $client,
    ) {
    }

    public static function make(MeilisearchClient $client): self
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
        $this->client->addDocuments($index, [$document]);
    }

    public function delete(string $index, string $id): void
    {
        $this->client->deleteDocument($index, trim($id));
    }

    public function search(string $index, string $query, array $options = []): SearchResult
    {
        $payload = $this->client->search($index, $query, $options);
        /** @var list<array<string, mixed>> $hits */
        $hits = array_values($payload['hits'] ?? []);
        $total = (int) ($payload['estimatedTotalHits'] ?? count($hits));

        return new SearchResult($query, $hits, $total);
    }

    public function clear(string $index): void
    {
        $this->client->deleteIndex($index);
    }
}
