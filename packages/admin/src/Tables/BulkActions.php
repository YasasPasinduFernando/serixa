<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Contextual bar of bulk-operation buttons, typically shown once rows are selected.
 *
 * @example BulkActions::make(Button::make('Delete')->danger())->summary('3 selected')
 */
final class BulkActions extends Component implements AdminWidgetInterface
{
    use HasChildren;
    use ResolvesAdminTheme;

    private ?string $summary = null;

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $actions = new self();
        $actions->setChildren(self::normalizeChildren($children));

        return $actions;
    }

    public function summary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function render(): string
    {
        $summaryHtml = $this->summary !== null
            ? sprintf('<span>%s</span>', $this->escape($this->summary))
            : '';

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.bulkActions'));

        return sprintf('<div%s>%s%s</div>', $attrs, $summaryHtml, $this->renderChildren($this->getChildren()));
    }
}
