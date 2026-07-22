<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class PluginsPanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('plugins', 'Plugins');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $tree = $data['inspectors']['plugin_tree'] ?? [];

        if (($tree['available'] ?? false) !== true) {
            return $this->section('Plugins', Html::tag('p', '', 'PluginManager not available.'), $data);
        }

        $body = '';
        foreach ($tree['plugins'] ?? [] as $plugin) {
            if (! is_array($plugin)) {
                continue;
            }

            $body .= Html::tag(
                'li',
                '',
                Html::escape(sprintf('%s (%s)', (string) ($plugin['name'] ?? ''), (string) ($plugin['class'] ?? ''))),
            );
        }

        if ($body === '') {
            $body = Html::tag('p', '', 'No plugins registered.');
        } else {
            $body = Html::tag('ul', ' class="list-disc pl-5"', $body);
        }

        return $this->section('Plugins', $body, $data);
    }
}
