<?php

declare(strict_types=1);

namespace Serixa\Forms\Binding;

final class RequestData
{
    /** @var array<string, mixed> */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build from PHP globals. POST values win over GET on key collision.
     */
    public static function fromGlobals(): self
    {
        /** @var array<string, mixed> $get */
        $get = $_GET;
        /** @var array<string, mixed> $post */
        $post = $_POST;

        return new self(array_merge($get, $post));
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function only(string ...$keys): array
    {
        $subset = [];

        foreach ($keys as $key) {
            if (array_key_exists($key, $this->data)) {
                $subset[$key] = $this->data[$key];
            }
        }

        return $subset;
    }

    public function boolean(string $key): bool
    {
        $value = $this->get($key);

        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (int) $value === 1;
        }

        if (is_string($value)) {
            return in_array(strtolower($value), ['1', 'true', 'on', 'yes'], true);
        }

        return false;
    }

    public function string(string $key, string $default = ''): string
    {
        $value = $this->get($key, $default);

        if ($value === null) {
            return $default;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return $default;
    }
}
