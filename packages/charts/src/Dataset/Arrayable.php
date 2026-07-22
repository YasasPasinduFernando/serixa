<?php

declare(strict_types=1);

namespace Serixa\Charts\Dataset;

/**
 * Optional contract for objects that expose a list/map suitable for chart data.
 */
interface Arrayable
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array;
}
