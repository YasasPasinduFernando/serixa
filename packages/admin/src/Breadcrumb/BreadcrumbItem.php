<?php

declare(strict_types=1);

namespace Serixa\Admin\Breadcrumb;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * A single crumb — a link, or the current (non-linked) page when
 * {@see current()} is set or no `href()` is given.
 *
 * @example BreadcrumbItem::make('Users')->href('/admin/users')
 * @example BreadcrumbItem::make('Edit user')->current()
 */
final class BreadcrumbItem extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $label;

    private ?string $href = null;

    private bool $current = false;

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

    public function current(bool $current = true): self
    {
        $this->current = $current;

        return $this;
    }

    public function isCurrent(): bool
    {
        return $this->current;
    }

    public function render(): string
    {
        if ($this->current || $this->href === null) {
            $attrs = $this->buildAttributeString(
                $this->theme()->classes('admin.breadcrumb.current'),
                ['aria-current' => $this->current ? 'page' : null],
            );

            return sprintf('<span%s>%s</span>', $attrs, $this->escape($this->label));
        }

        $attrs = $this->buildAttributeString(
            $this->theme()->classes('admin.breadcrumb.link'),
            ['href' => $this->href],
        );

        return sprintf('<a%s>%s</a>', $attrs, $this->escape($this->label));
    }
}
