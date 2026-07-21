<?php

declare(strict_types=1);

namespace Serixa\Admin\Components;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * A single term/description pair (`<dt>`/`<dd>`) inside a {@see DescriptionList}.
 *
 * @example DescriptionItem::make('Email', 'ada@example.com')
 */
final class DescriptionItem extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $term;

    private WidgetInterface|string $description;

    private function __construct(string $term, WidgetInterface|string $description)
    {
        parent::__construct();
        $this->term = $term;
        $this->description = $description;
    }

    public static function make(string $term, WidgetInterface|string $description): self
    {
        return new self($term, $description);
    }

    public function childrenForTree(): array
    {
        return $this->description instanceof WidgetInterface ? [$this->description] : [];
    }

    public function render(): string
    {
        return sprintf(
            '<div><dt%s>%s</dt><dd%s>%s</dd></div>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.descriptionItem.dt')),
            $this->escape($this->term),
            $this->buildStructuralAttributeString($this->theme()->classes('admin.descriptionItem.dd')),
            $this->renderChild($this->description),
        );
    }
}
