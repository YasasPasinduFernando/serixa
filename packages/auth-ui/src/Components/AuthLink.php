<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;

/**
 * Themed text link for auth footers and inline navigation.
 *
 * @example AuthLink::make('Forgot password?')->href('/forgot')
 */
final class AuthLink extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    private string $label;

    private string $href = '#';

    private function __construct(string $label)
    {
        parent::__construct();
        $this->label = $label;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function href(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('auth.link'),
            ['href' => $this->href],
        );

        return sprintf('<a%s>%s</a>', $attrs, $this->escape($this->label));
    }
}
