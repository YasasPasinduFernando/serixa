<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * Thin, readable convenience wrapper around {@see AppShell}.
 *
 * @example AdminLayout::shell()->sidebar(Sidebar::make(...))->topBar(TopBar::make(...))->content($page)
 */
final class AdminLayout extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private readonly AppShell $shell;

    private function __construct()
    {
        parent::__construct();
        $this->shell = AppShell::make();
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * Alias of {@see make()} for app-shell readability.
     */
    public static function shell(): self
    {
        return self::make();
    }

    public function sidebar(WidgetInterface|string|null $sidebar): self
    {
        $this->shell->sidebar($sidebar);

        return $this;
    }

    public function topBar(WidgetInterface|string|null $topBar): self
    {
        $this->shell->topBar($topBar);

        return $this;
    }

    public function content(WidgetInterface|string|null $content): self
    {
        $this->shell->content($content);

        return $this;
    }

    public function footer(WidgetInterface|string|null $footer): self
    {
        $this->shell->footer($footer);

        return $this;
    }

    public function childrenForTree(): array
    {
        return [$this->shell];
    }

    public function render(): string
    {
        return $this->renderChild($this->shell);
    }
}
