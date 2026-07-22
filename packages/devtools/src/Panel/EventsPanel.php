<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class EventsPanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('events', 'Events');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $timeline = $data['event_timeline'] ?? [];
        $events = $timeline['events'] ?? [];
        $body = '';

        if ($events === []) {
            $body = Html::tag('p', '', 'No events recorded.');
        } else {
            foreach ($events as $event) {
                $body .= Html::tag(
                    'div',
                    ' class="mb-1 font-mono text-xs"',
                    Html::escape(is_string($event) ? $event : (json_encode($event, JSON_UNESCAPED_SLASHES) ?: '')),
                );
            }
        }

        return $this->section('Event timeline', $body, $data);
    }
}
