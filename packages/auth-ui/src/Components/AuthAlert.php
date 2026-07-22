<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;

/**
 * Informational alert banner for auth cards (e.g. verify-email notice).
 *
 * @example AuthAlert::make('Check your inbox for a verification link.')
 */
final class AuthAlert extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    private string $message;

    private function __construct(string $message)
    {
        parent::__construct();
        $this->message = $message;
    }

    public static function make(string $message): self
    {
        return new self($message);
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('auth.alert'),
            ['role' => 'status'],
        );

        return Html::tag('div', $attrs, $this->escape($this->message));
    }
}
