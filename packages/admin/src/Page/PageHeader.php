<?php

declare(strict_types=1);

namespace Serixa\Admin\Page;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * Page title row: optional breadcrumb slot, title, description and actions.
 *
 * @example PageHeader::make()->title('Users')->description('Manage accounts')->actions(Button::make('Create')->primary())
 */
final class PageHeader extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private ?string $title = null;

    private ?string $description = null;

    private WidgetInterface|string|null $breadcrumb = null;

    /** @var list<WidgetInterface|string> */
    private array $actionItems = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function breadcrumb(WidgetInterface|string $breadcrumb): self
    {
        $this->breadcrumb = $breadcrumb;

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$actions
     */
    public function actions(WidgetInterface|string|array ...$actions): self
    {
        if (count($actions) === 1 && is_array($actions[0])) {
            /** @var list<WidgetInterface|string> $list */
            $list = array_values($actions[0]);
            $this->actionItems = $list;

            return $this;
        }

        /** @var list<WidgetInterface|string> $list */
        $list = [];

        foreach ($actions as $action) {
            if (is_array($action)) {
                continue;
            }

            $list[] = $action;
        }

        $this->actionItems = $list;

        return $this;
    }

    public function childrenForTree(): array
    {
        $nodes = [];

        if ($this->breadcrumb !== null) {
            $nodes[] = $this->breadcrumb;
        }

        return array_merge($nodes, $this->actionItems);
    }

    public function render(): string
    {
        $breadcrumbHtml = '';

        if ($this->breadcrumb !== null) {
            $breadcrumbHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.pageHeader.breadcrumb')),
                $this->renderChild($this->breadcrumb),
            );
        }

        $textHtml = '';

        if ($this->title !== null) {
            $textHtml .= $this->renderChild(PageTitle::make($this->title));
        }

        if ($this->description !== null) {
            $textHtml .= $this->renderChild(PageDescription::make($this->description));
        }

        $textBlock = sprintf(
            '<div%s>%s</div>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.pageHeader.text')),
            $textHtml,
        );

        $actionsHtml = '';

        if ($this->actionItems !== []) {
            $actionsHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.pageHeader.actions')),
                $this->renderChildren($this->actionItems),
            );
        }

        return sprintf(
            '%s<div%s>%s%s</div>',
            $breadcrumbHtml,
            $this->buildAttributeString($this->theme()->classes('admin.pageHeader')),
            $textBlock,
            $actionsHtml,
        );
    }
}
