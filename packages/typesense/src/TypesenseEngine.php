<?php

declare(strict_types=1);

namespace Serixa\Typesense;

use Serixa\Meilisearch\Contracts\SearchEngine;
use Serixa\Meilisearch\Exceptions\SearchException;
use Serixa\Meilisearch\SearchResult;
use Serixa\Typesense\Contracts\TypesenseClient;

final class TypesenseEngine implements SearchEngine
{
    public function __construct(
        private readonly TypesenseClient $client,
    ) {
    }

    public static function make(TypesenseClient $client): self
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
        $this->client->upsertDocument($index, $document);
    }

    public function delete(string $index, string $id): void
    {
        $this->client->deleteDocument($index, trim($id));
    }

    public function search(string $index, string $query, array $options = []): SearchResult
    {
        $payload = $this->client->search($index, $query, $options);
        $hits = [];
        foreach ($payload['hits'] ?? [] as $hit) {
            if (is_array($hit) && isset($hit['document']) && is_array($hit['document'])) {
                /** @var array<string, mixed> $doc */
                $doc = $hit['document'];
                $hits[] = $doc;
            }
        }
        $total = (int) ($payload['found'] ?? count($hits));

        return new SearchResult($query, $hits, $total);
    }

    public function clear(string $index): void
    {
        $this->client->deleteCollection($index);
    }
}
