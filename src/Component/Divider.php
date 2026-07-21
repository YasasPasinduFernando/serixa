<?php

declare(strict_types=1);

namespace NovaPHP\Component;

use NovaPHP\Component\Concerns\ResolvesTheme;

/**
 * Semantic horizontal rule.
 */
final class Divider extends Component
{
    use ResolvesTheme;

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('divider'));

        return sprintf('<hr%s />', $attrs);
    }
}
