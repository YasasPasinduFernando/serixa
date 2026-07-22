<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class ThemesPanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('themes', 'Themes');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $resolution = $data['theme_resolution'] ?? [];
        $tree = $data['inspectors']['theme_tree'] ?? [];

        $lines = Html::tag('li', '', Html::escape(sprintf('Active: %s', (string) ($resolution['class'] ?? 'n/a'))));
        $lines .= Html::tag(
            'li',
            '',
            Html::escape(sprintf('DevTools wrapped: %s', ($resolution['devtoolsWrapped'] ?? false) ? 'yes' : 'no')),
        );

        foreach ($tree['chain'] ?? [] as $index => $themeClass) {
            $lines .= Html::tag('li', '', Html::escape(sprintf('%d. %s', $index + 1, (string) $themeClass)));
        }

        return $this->section('Theme chain', Html::tag('ul', ' class="list-decimal pl-5 space-y-1"', $lines), $data);
    }
}
