<?php

declare(strict_types=1);

namespace Serixa\Forms\State;

final class ErrorsBag
{
    /** @var array<string, list<string>> */
    private array $messages;

    /**
     * @param array<string, list<string>|string> $messages
     */
    private function __construct(array $messages = [])
    {
        $normalized = [];

        foreach ($messages as $attribute => $value) {
            if (is_string($value)) {
                $normalized[$attribute] = [$value];
            } else {
                $normalized[$attribute] = array_values($value);
            }
        }

        $this->messages = $normalized;
    }

    /**
     * @param array<string, list<string>|string> $messages
     */
    public static function make(array $messages = []): self
    {
        return new self($messages);
    }

    public function add(string $attribute, string $message): self
    {
        if (! isset($this->messages[$attribute])) {
            $this->messages[$attribute] = [];
        }

        $this->messages[$attribute][] = $message;

        return $this;
    }

    public function has(string $attribute): bool
    {
        return isset($this->messages[$attribute]) && $this->messages[$attribute] !== [];
    }

    public function first(string $attribute): ?string
    {
        if (! $this->has($attribute)) {
            return null;
        }

        return $this->messages[$attribute][0];
    }

    /**
     * @return list<string>
     */
    public function get(string $attribute): array
    {
        return $this->messages[$attribute] ?? [];
    }

    /**
     * @return array<string, list<string>>
     */
    public function all(): array
    {
        return $this->messages;
    }

    public function any(): bool
    {
        return $this->messages !== [];
    }

    public function isEmpty(): bool
    {
        return $this->messages === [];
    }
}
