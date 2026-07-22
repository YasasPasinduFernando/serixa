<?php

declare(strict_types=1);

namespace Serixa\Gemini;

use Serixa\Gemini\Contracts\GeminiClient;

final class FakeGeminiClient implements GeminiClient
{
    public function generateContent(string $model, array $messages, array $options = []): array
    {
        $last = $messages === [] ? '' : (string) ($messages[array_key_last($messages)]['content'] ?? '');

        return [
            'content' => 'gemini: ' . $last,
            'model' => $model,
            'usage' => ['prompt_tokens' => 1, 'completion_tokens' => strlen($last) + 8],
        ];
    }
}
