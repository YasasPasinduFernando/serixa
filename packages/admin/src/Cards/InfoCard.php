<?php

declare(strict_types=1);

namespace Serixa\Admin\Cards;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Generic titled card for arbitrary body content.
 *
 * @example InfoCard::make(DescriptionList::make(...))->title('Account details')
 */
final class InfoCard extends Component implements AdminWidgetInterface
{
    use HasChildren;
    use ResolvesAdminTheme;

    private ?string $title = null;

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $card = new self();
        $card->setChildren(self::normalizeChildren($children));

        return $card;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function render(): string
    {
        $titleHtml = '';

        if ($this->title !== null) {
            $titleHtml = sprintf(
                '<h3%s>%s</h3>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.infoCard.title')),
                $this->escape($this->title),
            );
        }

        $bodyHtml = sprintf(
            '<div%s>%s</div>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.infoCard.body')),
            $this->renderChildren($this->getChildren()),
        );

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.infoCard'));

        return sprintf('<div%s>%s%s</div>', $attrs, $titleHtml, $bodyHtml);
    }
}
