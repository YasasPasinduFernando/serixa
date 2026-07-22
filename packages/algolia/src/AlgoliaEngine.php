<?php

declare(strict_types=1);

namespace Serixa\Algolia;

use Serixa\Algolia\Contracts\AlgoliaClient;
use Serixa\Meilisearch\Contracts\SearchEngine;
use Serixa\Meilisearch\Exceptions\SearchException;
use Serixa\Meilisearch\SearchResult;

final class AlgoliaEngine implements SearchEngine
{
    public function __construct(
        private readonly AlgoliaClient $client,
    ) {
    }

    public static function make(AlgoliaClient $client): self
    {
        return new self($client);
    }

    public function index(string $index, string $id, array $document): void
    {
        $id = trim($id);
        if ($id === '') {
            throw SearchException::invalidDocumentId($id);
        }
        $document['objectID'] = $id;
        $document['id'] = $id;
        $this->client->saveObject($index, $document);
    }

    public function delete(string $index, string $id): void
    {
        $this->client->deleteObject($index, trim($id));
    }

    public function search(string $index, string $query, array $options = []): SearchResult
    {
        $payload = $this->client->search($index, $query, $options);
        /** @var list<array<string, mixed>> $hits */
        $hits = array_values($payload['hits'] ?? []);
        $total = (int) ($payload['nbHits'] ?? count($hits));

        return new SearchResult($query, $hits, $total);
    }

    public function clear(string $index): void
    {
        $this->client->clearIndex($index);
    }
}
