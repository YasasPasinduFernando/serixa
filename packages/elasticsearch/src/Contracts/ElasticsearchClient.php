<?php

declare(strict_types=1);

namespace Serixa\Elasticsearch\Contracts;

interface ElasticsearchClient
{
    /**
     * @param array<string, mixed> $document
     */
    public function indexDocument(string $index, string $id, array $document): void;

    public function deleteDocument(string $index, string $id): void;

    /**
     * @param array<string, mixed> $options
     * @return array{hits: array{hits: list<array{_source: array<string, mixed>}>, total: array{value: int}}}
     */
    public function search(string $index, string $query, array $options = []): array;

    public function deleteIndex(string $index): void;
}
