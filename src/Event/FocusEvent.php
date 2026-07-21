<?php

declare(strict_types=1);

namespace NovaPHP\Event;

use NovaPHP\Contracts\EventInterface;

/** Prepared focus event contract — no browser implementation yet. */
final class FocusEvent implements EventInterface
{
    public function name(): string
    {
        return 'focus';
    }
}
