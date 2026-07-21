<?php

declare(strict_types=1);

namespace NovaPHP\Performance;

/**
 * Contract for memory sampling during render (implementation later).
 */
interface MemoryProfiler
{
    public function sample(string $label): int;

    public function peak(): int;
}
