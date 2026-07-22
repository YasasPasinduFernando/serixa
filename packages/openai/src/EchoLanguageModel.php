<?php

declare(strict_types=1);

namespace Serixa\Openai;

use Serixa\Openai\Contracts\LanguageModel;
use Serixa\Openai\Exceptions\AiException;

/**
 * Deterministic in-memory model for tests and offline demos.
 */
final class EchoLanguageModel implements LanguageModel
{
    public function __construct(
        private readonly string $model = 'echo',
        private readonly string $prefix = 'echo: ',
    ) {
    }

    public function chat(array $messages, array $options = []): ChatResponse
    {
        if ($messages === []) {
            throw AiException::emptyMessages();
        }

        $last = $messages[array_key_last($messages)];
        $content = $this->prefix . $last->content;

        return new ChatResponse($content, $this->model, [
            'prompt_tokens' => 0,
            'completion_tokens' => strlen($content),
        ]);
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
