<?php

declare(strict_types=1);

namespace Serixa\Cms;

use Serixa\Exception\InvalidArgumentException;

final class ContentBlock
{
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly string $body = '',
    ) {
        if ($id === '' || $type === '') {
            throw new InvalidArgumentException('Content block id and type are required.');
        }
    }

    public static function make(string $id, string $type, string $body = ''): self
    {
        return new self($id, $type, $body);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function body(): string
    {
        return $this->body;
    }
}
