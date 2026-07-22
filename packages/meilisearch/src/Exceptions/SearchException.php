<?php

declare(strict_types=1);

namespace Serixa\Meilisearch\Exceptions;

use RuntimeException;

final class SearchException extends RuntimeException
{
    public static function invalidIndex(string $index): self
    {
        return new self(sprintf('Invalid search index name: "%s".', $index));
    }

    public static function invalidDocumentId(string $id): self
    {
        return new self(sprintf('Invalid document id: "%s".', $id));
    }

    public static function notFound(string $index, string $id): self
    {
        return new self(sprintf('Document "%s" not found in index "%s".', $id, $index));
    }
}
