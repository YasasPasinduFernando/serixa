<?php

declare(strict_types=1);

namespace Serixa\Admin\Page;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * Top-level page composer: an optional {@see PageHeader} plus body widgets.
 * `title()` / `description()` / `actions()` are convenience helpers that
 * lazily build the header for the common case.
 *
 * @example Page::make(DataTable::make(...))->title('Users')->actions(Button::make('Create')->primary())
 */
final class Page extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private ?PageHeader $header = null;

    /** @var list<WidgetInterface|string> */
    private array $body = [];

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$body
     */
    public static function make(WidgetInterface|string|array ...$body): self
    {
        $page = new self();
        $page->body($body);

        return $page;
    }

    public function header(PageHeader $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function title(string $title): self
    {
        $this->header ??= PageHeader::make();
        $this->header->title($title);

        return $this;
    }

    public function description(string $description): self
    {
        $this->header ??= PageHeader::make();
        $this->header->description($description);

        return $this;
    }

    public function breadcrumb(WidgetInterface|string $breadcrumb): self
    {
        $this->header ??= PageHeader::make();
        $this->header->breadcrumb($breadcrumb);

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$actions
     */
    public function actions(WidgetInterface|string|array ...$actions): self
    {
        $this->header ??= PageHeader::make();
        $this->header->actions(...$actions);

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$body
     */
    public function body(WidgetInterface|string|array ...$body): self
    {
        if (count($body) === 1 && is_array($body[0])) {
            /** @var list<WidgetInterface|string> $list */
            $list = array_values($body[0]);
            $this->body = $list;

            return $this;
        }

        /** @var list<WidgetInterface|string> $list */
        $list = [];

        foreach ($body as $node) {
            if (is_array($node)) {
                continue;
            }

            $list[] = $node;
        }

        $this->body = $list;

        return $this;
    }

    public function childrenForTree(): array
    {
        $nodes = [];

        if ($this->header !== null) {
            $nodes[] = $this->header;
        }

        return array_merge($nodes, $this->body);
    }

    public function render(): string
    {
        $headerHtml = $this->header !== null ? $this->renderChild($this->header) : '';
        $bodyHtml = $this->renderChildren($this->body);

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.page'));

        return sprintf('<div%s>%s%s</div>', $attrs, $headerHtml, $bodyHtml);
    }
}
