<?php

declare(strict_types=1);

namespace Serixa\Ollama\Contracts;

interface OllamaClient
{
    /**
     * @param list<array{role: string, content: string}> $messages
     * @param array<string, mixed> $options
     * @return array{content: string, model: string, usage?: array<string, int>}
     */
    public function chat(string $model, array $messages, array $options = []): array;
}
