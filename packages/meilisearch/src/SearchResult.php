<?php

declare(strict_types=1);

namespace Serixa\Meilisearch;

final class SearchResult
{
    /**
     * @param list<array<string, mixed>> $hits
     */
    public function __construct(
        public readonly string $query,
        public readonly array $hits,
        public readonly int $estimatedTotalHits,
    ) {
    }

    public function isEmpty(): bool
    {
        return $this->hits === [];
    }

    public function count(): int
    {
        return count($this->hits);
    }
}
