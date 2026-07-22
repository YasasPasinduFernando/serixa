<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class PerformancePanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('performance', 'Performance');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $duration = $data['render_time']['durationMs'] ?? null;
        $memory = $data['memory_usage'] ?? [];
        $renderTree = $data['inspectors']['render_tree'] ?? [];

        $body = Html::tag('p', '', Html::escape(sprintf('Duration: %s ms', $duration ?? 'n/a')));
        $body .= Html::tag('p', '', Html::escape(sprintf(
            'Memory: %s (peak %s)',
            number_format((int) ($memory['currentBytes'] ?? 0)),
            number_format((int) ($memory['peakBytes'] ?? 0)),
        )));

        if (isset($renderTree['nodeCount'])) {
            $body .= Html::tag('p', '', Html::escape(sprintf('Tree nodes: %d', (int) $renderTree['nodeCount'])));
        }

        return $this->section('Performance', $body, $data);
    }
}
