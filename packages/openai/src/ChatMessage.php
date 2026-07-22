<?php

declare(strict_types=1);

namespace Serixa\Openai;

use Serixa\Openai\Exceptions\AiException;

final class ChatMessage
{
    public const ROLE_SYSTEM = 'system';
    public const ROLE_USER = 'user';
    public const ROLE_ASSISTANT = 'assistant';

    public function __construct(
        public readonly string $role,
        public readonly string $content,
    ) {
        if (! in_array($role, [self::ROLE_SYSTEM, self::ROLE_USER, self::ROLE_ASSISTANT], true)) {
            throw AiException::invalidRole($role);
        }
    }

    public static function system(string $content): self
    {
        return new self(self::ROLE_SYSTEM, $content);
    }

    public static function user(string $content): self
    {
        return new self(self::ROLE_USER, $content);
    }

    public static function assistant(string $content): self
    {
        return new self(self::ROLE_ASSISTANT, $content);
    }

    /**
     * @return array{role: string, content: string}
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
        ];
    }
}
