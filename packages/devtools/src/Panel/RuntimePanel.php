<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class RuntimePanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('runtime', 'Runtime');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $pe = $data['progressive_enhancement'] ?? [];
        $cli = $data['cli_commands'] ?? [];

        $body = Html::tag(
            'p',
            '',
            Html::escape(sprintf('Serixa JS detected: %s', ($pe['hasSerixaJs'] ?? false) ? 'yes' : 'no')),
        );

        $attrs = $pe['dataSerixaAttributes'] ?? [];
        if (is_array($attrs) && $attrs !== []) {
            $body .= Html::tag('p', '', Html::escape('data-serixa-* attributes: ' . implode(', ', $attrs)));
        }

        if (($cli['available'] ?? false) === true) {
            $commands = is_array($cli['commands'] ?? null) ? implode(', ', $cli['commands']) : '';
            $body .= Html::tag('p', ' class="mt-2"', Html::escape('CLI commands: ' . $commands));
        }

        return $this->section('Runtime', $body, $data);
    }
}
