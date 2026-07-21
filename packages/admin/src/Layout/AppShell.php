<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Scaffold;
use Serixa\Contracts\WidgetInterface;

/**
 * Application shell composing a {@see Scaffold} with admin-styled regions.
 * Purely structural — no routing, auth, or client-side JavaScript.
 *
 * @example AppShell::make()->sidebar(Sidebar::make(...))->topBar(TopBar::make(...))->content(MainContent::make(...))
 */
final class AppShell extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private WidgetInterface|string|null $sidebarSlot = null;

    private WidgetInterface|string|null $topBarSlot = null;

    private WidgetInterface|string|null $contentSlot = null;

    private WidgetInterface|string|null $footerSlot = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function sidebar(WidgetInterface|string|null $sidebar): self
    {
        $this->sidebarSlot = $sidebar;

        return $this;
    }

    public function topBar(WidgetInterface|string|null $topBar): self
    {
        $this->topBarSlot = $topBar;

        return $this;
    }

    public function content(WidgetInterface|string|null $content): self
    {
        $this->contentSlot = $content;

        return $this;
    }

    public function footer(WidgetInterface|string|null $footer): self
    {
        $this->footerSlot = $footer;

        return $this;
    }

    public function childrenForTree(): array
    {
        $nodes = [];

        foreach ([$this->topBarSlot, $this->sidebarSlot, $this->contentSlot, $this->footerSlot] as $slot) {
            if ($slot !== null) {
                $nodes[] = $slot;
            }
        }

        return $nodes;
    }

    public function render(): string
    {
        $scaffold = Scaffold::make()
            ->appBar($this->topBarSlot)
            ->sidebar($this->sidebarSlot)
            ->body($this->contentSlot)
            ->footer($this->footerSlot);

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.shell'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChild($scaffold));
    }
}
