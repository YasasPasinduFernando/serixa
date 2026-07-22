<?php

declare(strict_types=1);

namespace Serixa\Ollama;

use Serixa\Ollama\Contracts\OllamaClient;

final class FakeOllamaClient implements OllamaClient
{
    public function chat(string $model, array $messages, array $options = []): array
    {
        $last = $messages === [] ? '' : (string) ($messages[array_key_last($messages)]['content'] ?? '');

        return [
            'content' => 'ollama: ' . $last,
            'model' => $model,
            'usage' => ['prompt_tokens' => 1, 'completion_tokens' => strlen($last) + 8],
        ];
    }
}
