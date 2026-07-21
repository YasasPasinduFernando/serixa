<?php

declare(strict_types=1);

namespace Serixa\Admin\UserMenu;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Avatar;
use Serixa\Component\Component;

/**
 * Account menu: avatar + name trigger, disclosed via native `<details>`/`<summary>`
 * (no JavaScript). Items are plain links — sign-out is UI only, no auth logic.
 *
 * @example UserMenu::make('Ada Lovelace')->item('Profile', '/admin/profile')->divider()->item('Sign out', '/logout', danger: true)
 */
final class UserMenu extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $name;

    private Avatar $avatar;

    /** @var list<array{type: string, label?: string, href?: string, danger?: bool}> */
    private array $items = [];

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

    public function item(string $label, string $href, bool $danger = false): self
    {
        $this->items[] = ['type' => 'item', 'label' => $label, 'href' => $href, 'danger' => $danger];

        return $this;
    }

    public function divider(): self
    {
        $this->items[] = ['type' => 'divider'];

        return $this;
    }

    public function childrenForTree(): array
    {
        return [$this->avatar];
    }

    public function render(): string
    {
        $triggerHtml = sprintf(
            '<summary%s>%s<span%s>%s</span></summary>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.userMenu.trigger')),
            $this->renderChild($this->avatar),
            $this->buildStructuralAttributeString($this->theme()->classes('admin.userChip.name')),
            $this->escape($this->name),
        );

        $itemsHtml = '';

        foreach ($this->items as $entry) {
            if ($entry['type'] === 'divider') {
                $itemsHtml .= sprintf(
                    '<hr%s />',
                    $this->buildStructuralAttributeString($this->theme()->classes('admin.userMenu.divider')),
                );
                continue;
            }

            $itemsHtml .= sprintf(
                '<a%s>%s</a>',
                $this->buildStructuralAttributeString(
                    $this->theme()->classes('admin.userMenu.item', ['danger' => $entry['danger'] ?? false]),
                    ['href' => $entry['href'] ?? '#'],
                ),
                $this->escape($entry['label'] ?? ''),
            );
        }

        $panelHtml = sprintf(
            '<div%s>%s</div>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.userMenu.panel')),
            $itemsHtml,
        );

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.userMenu'));

        return sprintf('<details%s>%s%s</details>', $attrs, $triggerHtml, $panelHtml);
    }
}
