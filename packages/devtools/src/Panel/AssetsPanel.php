<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class AssetsPanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('assets', 'Assets');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $loading = $data['asset_loading'] ?? [];
        $tree = $data['inspectors']['asset_tree'] ?? [];

        $scripts = count($loading['scripts'] ?? []);
        $styles = count($loading['styles'] ?? []);

        $body = Html::tag('p', '', Html::escape(sprintf('%d scripts, %d stylesheets', $scripts, $styles)));

        $list = '';
        foreach ($tree['assets'] ?? [] as $asset) {
            if (! is_array($asset)) {
                continue;
            }

            $list .= Html::tag(
                'li',
                '',
                Html::escape(sprintf('[%s] %s', (string) ($asset['type'] ?? '?'), (string) ($asset['path'] ?? ''))),
            );
        }

        if ($list !== '') {
            $body .= Html::tag('ul', ' class="list-disc pl-5 mt-2"', $list);
        }

        return $this->section('Assets', $body, $data);
    }
}
