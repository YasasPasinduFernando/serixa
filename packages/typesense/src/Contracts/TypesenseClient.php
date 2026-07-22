<?php

declare(strict_types=1);

namespace Serixa\Typesense\Contracts;

interface TypesenseClient
{
    /**
     * @param array<string, mixed> $document
     */
    public function upsertDocument(string $collection, array $document): void;

    public function deleteDocument(string $collection, string $id): void;

    /**
     * @param array<string, mixed> $options
     * @return array{hits: list<array{document: array<string, mixed>}>, found: int}
     */
    public function search(string $collection, string $query, array $options = []): array;

    public function deleteCollection(string $collection): void;
}
