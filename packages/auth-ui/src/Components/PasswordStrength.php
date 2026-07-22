<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;

/**
 * UI-only password strength meter based on length heuristics (not cryptographic).
 *
 * Levels: empty, weak (&lt;6), fair (6–7), good (8–11), strong (12+).
 *
 * @example PasswordStrength::make($password)->label('Password strength')
 */
final class PasswordStrength extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    private ?string $password;

    private string $labelText = 'Password strength';

    private function __construct(?string $password)
    {
        parent::__construct();
        $this->password = $password;
    }

    public static function make(?string $password = null): self
    {
        return new self($password);
    }

    public function value(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function label(string $label): self
    {
        $this->labelText = $label;

        return $this;
    }

    public function render(): string
    {
        $level = $this->resolveLevel($this->password ?? '');

        $labelHtml = sprintf(
            '<span>%s: %s</span>',
            $this->escape($this->labelText),
            $this->escape(ucfirst($level === 'empty' ? 'none' : $level)),
        );

        $fillHtml = '';

        if ($level !== 'empty') {
            $fillHtml = sprintf(
                '<div%s></div>',
                $this->buildStructuralAttributeString($this->theme()->classes('auth.strength.' . $level)),
            );
        }

        $bar = sprintf(
            '<div%s>%s</div>',
            $this->buildStructuralAttributeString($this->theme()->classes('auth.strength.bar')),
            $fillHtml,
        );

        $attrs = $this->buildAttributeString(
            $this->theme()->classes('auth.strength', ['level' => $level]),
            [
                'role' => 'status',
                'aria-live' => 'polite',
            ],
        );

        return Html::tag('div', $attrs, $labelHtml . $bar);
    }

    /**
     * @return 'empty'|'weak'|'fair'|'good'|'strong'
     */
    private function resolveLevel(string $password): string
    {
        $length = strlen($password);

        if ($length === 0) {
            return 'empty';
        }

        if ($length < 6) {
            return 'weak';
        }

        if ($length < 8) {
            return 'fair';
        }

        if ($length < 12) {
            return 'good';
        }

        return 'strong';
    }
}
