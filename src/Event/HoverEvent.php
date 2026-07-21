<?php

declare(strict_types=1);

namespace NovaPHP\Event;

use NovaPHP\Contracts\EventInterface;

/** Prepared hover event contract — no browser implementation yet. */
final class HoverEvent implements EventInterface
{
    public function name(): string
    {
        return 'hover';
    }
}
