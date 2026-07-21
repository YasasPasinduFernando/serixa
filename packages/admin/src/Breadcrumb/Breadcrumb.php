<?php

declare(strict_types=1);

namespace Serixa\Admin\Breadcrumb;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Breadcrumb navigation landmark (`<nav aria-label="Breadcrumb">`).
 *
 * @example Breadcrumb::make(BreadcrumbItem::make('Admin')->href('/admin'), BreadcrumbItem::make('Users')->current())
 */
final class Breadcrumb extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    /** @var list<BreadcrumbItem> */
    private array $items = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(BreadcrumbItem ...$items): self
    {
        $breadcrumb = new self();
        $breadcrumb->items = array_values($items);

        return $breadcrumb;
    }

    public function items(BreadcrumbItem ...$items): self
    {
        $this->items = array_values($items);

        return $this;
    }

    public function childrenForTree(): array
    {
        return $this->items;
    }

    public function render(): string
    {
        $count = count($this->items);
        $itemsHtml = '';

        foreach ($this->items as $index => $item) {
            $separator = $index < $count - 1
                ? sprintf(
                    '<span%s aria-hidden="true">/</span>',
                    $this->buildStructuralAttributeString($this->theme()->classes('admin.breadcrumb.separator')),
                )
                : '';

            $itemsHtml .= sprintf(
                '<li%s>%s%s</li>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.breadcrumb.item')),
                $this->renderChild($item),
                $separator,
            );
        }

        $navAttrs = $this->buildAttributeString(
            $this->theme()->classes('admin.breadcrumb'),
            ['aria-label' => 'Breadcrumb'],
        );

        $listAttrs = $this->buildStructuralAttributeString($this->theme()->classes('admin.breadcrumb.list'));

        return sprintf('<nav%s><ol%s>%s</ol></nav>', $navAttrs, $listAttrs, $itemsHtml);
    }
}
