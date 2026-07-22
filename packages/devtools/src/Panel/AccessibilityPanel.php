<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class AccessibilityPanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('accessibility', 'Accessibility');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $issues = $data['accessibility']['issues'] ?? [];
        $body = '';

        if ($issues === []) {
            $body = Html::tag('p', '', 'No issues detected.');
        } else {
            foreach ($issues as $issue) {
                if (! is_array($issue)) {
                    continue;
                }

                $body .= Html::tag(
                    'div',
                    ' class="mb-2 p-2 border border-amber-600 rounded"',
                    Html::escape((string) ($issue['message'] ?? 'Unknown issue')),
                );
            }
        }

        return $this->section('Accessibility', $body, $data);
    }
}
