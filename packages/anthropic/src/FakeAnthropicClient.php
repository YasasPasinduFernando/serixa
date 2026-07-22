<?php

declare(strict_types=1);

namespace Serixa\Anthropic;

use Serixa\Anthropic\Contracts\AnthropicClient;

final class FakeAnthropicClient implements AnthropicClient
{
    public function messages(string $model, array $messages, array $options = []): array
    {
        $last = $messages === [] ? '' : (string) ($messages[array_key_last($messages)]['content'] ?? '');

        return [
            'content' => 'anthropic: ' . $last,
            'model' => $model,
            'usage' => ['prompt_tokens' => 1, 'completion_tokens' => strlen($last) + 11],
        ];
    }
}
