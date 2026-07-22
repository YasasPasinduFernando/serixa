<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;

final class RenderTimeCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('render_time');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        $startedAt = $ctx->startedAt();

        if ($startedAt === null) {
            return ['durationMs' => null];
        }

        $durationMs = (hrtime(true) - $startedAt) / 1_000_000;

        return [
            'durationMs' => round($durationMs, 3),
        ];
    }
}
