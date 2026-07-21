<?php

declare(strict_types=1);

namespace NovaPHP\Event;

use NovaPHP\Contracts\EventInterface;

/** Prepared click event contract — no browser implementation yet. */
final class ClickEvent implements EventInterface
{
    public function name(): string
    {
        return 'click';
    }
}
