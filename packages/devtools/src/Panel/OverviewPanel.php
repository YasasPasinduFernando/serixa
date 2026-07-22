<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class OverviewPanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('overview', 'Overview');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $renderTime = $data['render_time']['durationMs'] ?? null;
        $widgetCount = $data['widget_count']['count'] ?? 0;
        $memory = $data['memory_usage']['currentBytes'] ?? 0;
        $a11yIssues = $data['accessibility']['issueCount'] ?? 0;
        $theme = $data['theme_resolution']['class'] ?? 'unknown';

        $items = Html::tag('li', '', Html::escape(sprintf('Render: %s ms', $renderTime ?? 'n/a')));
        $items .= Html::tag('li', '', Html::escape(sprintf('Widgets: %d', (int) $widgetCount)));
        $items .= Html::tag('li', '', Html::escape(sprintf('Memory: %s bytes', number_format((int) $memory))));
        $items .= Html::tag('li', '', Html::escape(sprintf('A11y issues: %d', (int) $a11yIssues)));
        $items .= Html::tag('li', '', Html::escape(sprintf('Theme: %s', (string) $theme)));

        return $this->section('Overview', Html::tag('ul', ' class="list-disc pl-5 space-y-1"', $items), $data);
    }
}
