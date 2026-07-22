<?php

declare(strict_types=1);

namespace Serixa\Openai;

use Serixa\Openai\Contracts\OpenAiClient;

final class FakeOpenAiClient implements OpenAiClient
{
    public function __construct(
        private readonly string $prefix = 'openai: ',
    ) {
    }

    public function chatCompletions(string $model, array $messages, array $options = []): array
    {
        $last = $messages === [] ? '' : (string) ($messages[array_key_last($messages)]['content'] ?? '');
        $content = $this->prefix . $last;

        return [
            'content' => $content,
            'model' => $model,
            'usage' => [
                'prompt_tokens' => 1,
                'completion_tokens' => strlen($content),
            ],
        ];
    }
}
