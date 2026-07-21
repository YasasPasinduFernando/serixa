<?php

declare(strict_types=1);

namespace Serixa\Admin\Toolbar;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\AssertsAdminOption;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasDomEvents;
use Serixa\Icons\Component\Icon;

/**
 * Button-like toolbar action with an optional icon. Renders as `<a>` when
 * {@see href()} is set, otherwise as a `<button type="button">`.
 *
 * @example ToolbarAction::make('Create')->icon('plus')->primary()
 * @example ToolbarAction::make('View')->href('/admin/users/1')
 */
final class ToolbarAction extends Component implements AdminWidgetInterface
{
    use AssertsAdminOption;
    use HasDomEvents;
    use ResolvesAdminTheme;

    private string $label;

    private ?string $icon = null;

    private ?string $href = null;

    private string $variant = 'default';

    private bool $disabled = false;

    private function __construct(string $label)
    {
        parent::__construct();
        $this->label = $label;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function href(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function variant(string $variant): self
    {
        $this->assertOption($variant, ['default', 'primary', 'danger', 'ghost'], 'variant');
        $this->variant = $variant;

        return $this;
    }

    public function primary(): self
    {
        return $this->variant('primary');
    }

    public function danger(): self
    {
        return $this->variant('danger');
    }

    public function ghost(): self
    {
        return $this->variant('ghost');
    }

    public function disabled(bool $disabled = true): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function childrenForTree(): array
    {
        return $this->icon !== null ? [Icon::make($this->icon)] : [];
    }

    public function render(): string
    {
        $iconHtml = '';

        if ($this->icon !== null) {
            $iconHtml = sprintf(
                '<span%s aria-hidden="true">%s</span>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.toolbar.action.icon')),
                $this->renderChild(Icon::make($this->icon)->size('sm')),
            );
        }

        $state = ['variant' => $this->variant, 'disabled' => $this->disabled];

        if ($this->href !== null) {
            $attrs = $this->buildAttributeString(
                $this->theme()->classes('admin.toolbar.action', $state),
                [
                    'href' => $this->href,
                    'aria-disabled' => $this->disabled ? 'true' : null,
                ],
            );

            return sprintf('<a%s>%s%s</a>', $attrs, $iconHtml, $this->escape($this->label));
        }

        $attrs = $this->buildAttributeString(
            $this->theme()->classes('admin.toolbar.action', $state),
            array_merge($this->domEventAttributes(), [
                'type' => 'button',
                'disabled' => $this->disabled ? true : null,
                'aria-disabled' => $this->disabled ? 'true' : null,
            ]),
        );

        return sprintf('<button%s>%s%s</button>', $attrs, $iconHtml, $this->escape($this->label));
    }
}
