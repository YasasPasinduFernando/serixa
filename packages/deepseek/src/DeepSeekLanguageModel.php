<?php

declare(strict_types=1);

namespace Serixa\Deepseek;

use Serixa\Deepseek\Contracts\DeepSeekClient;
use Serixa\Openai\ChatMessage;
use Serixa\Openai\ChatResponse;
use Serixa\Openai\Contracts\LanguageModel;
use Serixa\Openai\Exceptions\AiException;

final class DeepSeekLanguageModel implements LanguageModel
{
    public function __construct(
        private readonly DeepSeekClient $client,
        private readonly string $model = 'deepseek-chat',
    ) {
        if (trim($model) === '') {
            throw AiException::emptyModel();
        }
    }

    public static function make(DeepSeekClient $client, string $model = 'deepseek-chat'): self
    {
        return new self($client, $model);
    }

    public function chat(array $messages, array $options = []): ChatResponse
    {
        if ($messages === []) {
            throw AiException::emptyMessages();
        }

        $payload = [];
        foreach ($messages as $message) {
            $payload[] = $message->toArray();
        }

        $model = (string) ($options['model'] ?? $this->model);
        $result = $this->client->chatCompletions($model, $payload, $options);
        /** @var array<string, int> $usage */
        $usage = $result['usage'] ?? [];

        return new ChatResponse((string) ($result['content'] ?? ''), (string) ($result['model'] ?? $model), $usage);
    }

    public function complete(string $prompt, array $options = []): ChatResponse
    {
        $prompt = trim($prompt);
        if ($prompt === '') {
            throw AiException::emptyPrompt();
        }

        return $this->chat([ChatMessage::user($prompt)], $options);
    }
}
