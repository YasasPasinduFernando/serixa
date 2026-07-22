<?php

declare(strict_types=1);

namespace Serixa\Openai;

final class ChatResponse
{
    /**
     * @param array<string, int> $usage
     */
    public function __construct(
        public readonly string $content,
        public readonly string $model,
        public readonly array $usage = [],
    ) {
    }

    public function isEmpty(): bool
    {
        return trim($this->content) === '';
    }
}
