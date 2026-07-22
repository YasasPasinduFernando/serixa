<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;

/**
 * Horizontal “or” divider between primary and social auth actions.
 *
 * @example AuthDivider::make('or')
 */
final class AuthDivider extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    private string $label;

    private function __construct(string $label)
    {
        parent::__construct();
        $this->label = $label;
    }

    public static function make(string $label = 'or'): self
    {
        return new self($label);
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('auth.divider'));

        return Html::tag('div', $attrs, sprintf('<span>%s</span>', $this->escape($this->label)));
    }
}
