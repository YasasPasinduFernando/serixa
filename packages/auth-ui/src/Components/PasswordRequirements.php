<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;
use Serixa\Icons\Component\Icon;

/**
 * Checklist of password requirements with check / x-mark icons.
 *
 * @example PasswordRequirements::make()->requirement('At least 8 characters', true)
 */
final class PasswordRequirements extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    /** @var list<array{label: string, met: bool}> */
    private array $requirements = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function requirement(string $label, bool $met = false): self
    {
        $this->requirements[] = [
            'label' => $label,
            'met' => $met,
        ];

        return $this;
    }

    public function render(): string
    {
        if ($this->requirements === []) {
            return '';
        }

        $items = '';

        foreach ($this->requirements as $requirement) {
            $iconName = $requirement['met'] ? 'check' : 'x-mark';
            $icon = Icon::make($iconName)
                ->size('sm')
                ->color($requirement['met'] ? 'success' : 'muted')
                ->decorative()
                ->render();

            $itemAttrs = $this->buildStructuralAttributeString(
                $this->theme()->classes('auth.requirements.item', ['met' => $requirement['met']]),
                ['role' => 'listitem'],
            );

            $items .= sprintf(
                '<li%s>%s<span>%s</span></li>',
                $itemAttrs,
                $icon,
                $this->escape($requirement['label']),
            );
        }

        $attrs = $this->buildAttributeString(
            $this->theme()->classes('auth.requirements'),
            ['role' => 'list'],
        );

        return Html::tag('ul', $attrs, $items);
    }
}
