<?php

declare(strict_types=1);

namespace Serixa\Gemini\Contracts;

interface GeminiClient
{
    /**
     * @param list<array{role: string, content: string}> $messages
     * @param array<string, mixed> $options
     * @return array{content: string, model: string, usage?: array<string, int>}
     */
    public function generateContent(string $model, array $messages, array $options = []): array;
}
