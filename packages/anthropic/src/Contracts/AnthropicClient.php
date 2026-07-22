<?php

declare(strict_types=1);

namespace Serixa\Anthropic\Contracts;

interface AnthropicClient
{
    /**
     * @param list<array{role: string, content: string}> $messages
     * @param array<string, mixed> $options
     * @return array{content: string, model: string, usage?: array<string, int>}
     */
    public function messages(string $model, array $messages, array $options = []): array;
}
