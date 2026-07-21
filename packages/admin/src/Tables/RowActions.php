<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Compact right-aligned row of action links, meant for the trailing cell of a table row.
 *
 * @example RowActions::make()->link('Edit', '/admin/users/1/edit')->link('Delete', '/admin/users/1/delete')
 */
final class RowActions extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    /** @var list<array{label: string, href: string}> */
    private array $items = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function link(string $label, string $href): self
    {
        $this->items[] = ['label' => $label, 'href' => $href];

        return $this;
    }

    public function render(): string
    {
        $itemsHtml = '';

        foreach ($this->items as $item) {
            $itemsHtml .= sprintf(
                '<a%s>%s</a>',
                $this->buildStructuralAttributeString(
                    $this->theme()->classes('admin.rowActions.item'),
                    ['href' => $item['href']],
                ),
                $this->escape($item['label']),
            );
        }

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.rowActions'));

        return sprintf('<div%s>%s</div>', $attrs, $itemsHtml);
    }
}
