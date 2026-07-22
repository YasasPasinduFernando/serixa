<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;

final class EventTimelineCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('event_timeline');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        $events = $ctx->extras()['events'] ?? [];

        if (! is_array($events)) {
            $events = [];
        }

        return [
            'events' => array_values($events),
            'count' => count($events),
        ];
    }
}
