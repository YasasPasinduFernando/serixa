<?php

declare(strict_types=1);

namespace Serixa\Openai\Contracts;

use Serixa\Openai\ChatMessage;
use Serixa\Openai\ChatResponse;

/**
 * Shared chat/completion contract for OpenAI and other LLM adapters.
 */
interface LanguageModel
{
    /**
     * @param list<ChatMessage> $messages
     * @param array<string, mixed> $options
     */
    public function chat(array $messages, array $options = []): ChatResponse;

    /**
     * @param array<string, mixed> $options
     */
    public function complete(string $prompt, array $options = []): ChatResponse;
}
