<?php

declare(strict_types=1);

namespace Serixa\Admin\Components;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Avatar;
use Serixa\Component\Component;

/**
 * Avatar + name (and optional subtitle), e.g. for tables or the {@see \Serixa\Admin\UserMenu\UserMenu} trigger.
 *
 * @example UserChip::make('Ada Lovelace')->subtitle('ada@example.com')
 */
final class UserChip extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $name;

    private ?string $subtitle = null;

    private Avatar $avatar;

    private function __construct(string $name)
    {
        parent::__construct();
        $this->name = $name;
        $this->avatar = Avatar::make()->initials($name)->size('sm');
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function avatar(Avatar $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function subtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function childrenForTree(): array
    {
        return [$this->avatar];
    }

    public function render(): string
    {
        $nameHtml = sprintf(
            '<span%s>%s</span>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.userChip.name')),
            $this->escape($this->name),
        );

        $subtitleHtml = '';

        if ($this->subtitle !== null) {
            $subtitleHtml = sprintf(
                '<span%s>%s</span>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.userChip.subtitle')),
                $this->escape($this->subtitle),
            );
        }

        $textHtml = sprintf(
            '<span%s>%s%s</span>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.userChip.text')),
            $nameHtml,
            $subtitleHtml,
        );

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.userChip'));

        return sprintf('<span%s>%s%s</span>', $attrs, $this->renderChild($this->avatar), $textHtml);
    }
}
