<?php

declare(strict_types=1);

namespace Serixa\Admin\EmptyState;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\EmptyState as FrameworkEmptyState;
use Serixa\Contracts\WidgetInterface;
use Serixa\Icons\Component\Icon;

/**
 * Admin-styled wrapper around {@see FrameworkEmptyState}, defaulting to an
 * {@see Icon} instead of a raw glyph.
 *
 * @example EmptyState::make()->icon('inbox')->title('No results')->description('Try a different search.')
 */
final class EmptyState extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private readonly FrameworkEmptyState $inner;

    private function __construct()
    {
        parent::__construct();
        $this->inner = FrameworkEmptyState::make();
    }

    public static function make(): self
    {
        return new self();
    }

    public function icon(string $icon): self
    {
        $this->inner->icon(Icon::make($icon)->size('2xl')->color('muted'));

        return $this;
    }

    public function title(string $title): self
    {
        $this->inner->title($title);

        return $this;
    }

    public function description(string $description): self
    {
        $this->inner->description($description);

        return $this;
    }

    public function action(WidgetInterface|string $action): self
    {
        $this->inner->action($action);

        return $this;
    }

    public function childrenForTree(): array
    {
        return [$this->inner];
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.emptyState'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChild($this->inner));
    }
}
