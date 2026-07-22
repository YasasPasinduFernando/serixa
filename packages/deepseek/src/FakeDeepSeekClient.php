<?php

declare(strict_types=1);

namespace Serixa\Deepseek;

use Serixa\Deepseek\Contracts\DeepSeekClient;

final class FakeDeepSeekClient implements DeepSeekClient
{
    public function chatCompletions(string $model, array $messages, array $options = []): array
    {
        $last = $messages === [] ? '' : (string) ($messages[array_key_last($messages)]['content'] ?? '');

        return [
            'content' => 'deepseek: ' . $last,
            'model' => $model,
            'usage' => ['prompt_tokens' => 1, 'completion_tokens' => strlen($last) + 10],
        ];
    }
}
