<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;

final class MemoryUsageCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('memory_usage');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        return [
            'currentBytes' => memory_get_usage(true),
            'peakBytes' => memory_get_peak_usage(true),
        ];
    }
}
