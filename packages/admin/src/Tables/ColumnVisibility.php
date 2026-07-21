<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Dropdown-looking "toggle columns" UI, rendered as a native `<details>` disclosure.
 * SSR-only placeholder — checkboxes are static (no client-side toggling).
 *
 * @example ColumnVisibility::make()->column('Name')->column('Email')->column('Created at', checked: false)
 */
final class ColumnVisibility extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $label = 'Columns';

    /** @var list<array{label: string, checked: bool}> */
    private array $columns = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function column(string $label, bool $checked = true): self
    {
        $this->columns[] = ['label' => $label, 'checked' => $checked];

        return $this;
    }

    public function render(): string
    {
        $itemsHtml = '';

        foreach ($this->columns as $column) {
            $checkboxAttrs = $this->attributeBag->build([
                'type' => 'checkbox',
                'checked' => $column['checked'] ? true : null,
                'disabled' => true,
            ]);

            $itemsHtml .= sprintf(
                '<label%s><input%s /><span>%s</span></label>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.columnVisibility.item')),
                $checkboxAttrs,
                $this->escape($column['label']),
            );
        }

        $summaryHtml = sprintf(
            '<summary%s>%s</summary>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.columnVisibility.trigger')),
            $this->escape($this->label),
        );

        $panelHtml = sprintf(
            '<div%s>%s</div>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.columnVisibility.panel')),
            $itemsHtml,
        );

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.columnVisibility'));

        return sprintf('<details%s>%s%s</details>', $attrs, $summaryHtml, $panelHtml);
    }
}
