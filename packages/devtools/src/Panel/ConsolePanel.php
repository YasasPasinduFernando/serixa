<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Support\Html;

final class ConsolePanel extends AbstractPanel
{
    public function __construct()
    {
        parent::__construct('console', 'Console');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string
    {
        $consoleClasses = $this->classString($this->theme()->classes('devtools.console'));
        $payload = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}';

        $body = Html::tag('pre', ' class="' . Html::escape($consoleClasses) . '"', Html::escape($payload));

        return $this->section('Console payload', $body, $data);
    }
}
