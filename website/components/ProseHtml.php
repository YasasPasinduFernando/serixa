<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Component;

/**
 * Renders trusted first-party documentation HTML inside a prose container.
 */
final class ProseHtml extends Component
{
    private function __construct(
        private readonly string $html,
    ) {
        parent::__construct();
    }

    public static function make(string $html): self
    {
        return new self($html);
    }

    public function render(): string
    {
        return '<div class="doc-prose">' . $this->html . '</div>';
    }
}
