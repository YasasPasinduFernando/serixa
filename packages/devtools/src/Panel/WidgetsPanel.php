<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class WidgetsPanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('widgets', 'Widgets');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $count = (int) ($data['widget_count']['count'] ?? 0);
        $textNodes = (int) ($data['widget_count']['textNodes'] ?? 0);
        $tree = $data['inspectors']['widget_tree']['tree'] ?? null;

        $summary = Html::tag(
            'p',
            '',
            Html::escape(sprintf('%d widgets, %d text nodes', $count, $textNodes)),
        );

        $treeJson = Html::tag(
            'pre',
            ' class="' . Html::escape($this->classString($this->theme()->classes('devtools.console'))) . '"',
            Html::escape(json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: 'null'),
        );

        return $this->section('Widget tree', $summary . $treeJson, $data);
    }
}
