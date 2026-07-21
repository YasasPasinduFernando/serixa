<?php

declare(strict_types=1);

namespace Serixa\Admin\Components;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Avatar;
use Serixa\Component\Component;

/**
 * Overlapping stack of {@see Avatar} widgets with an optional "+N" overflow badge.
 *
 * @example AvatarGroup::make(Avatar::make()->initials('AL'), Avatar::make()->initials('BC'))->max(3)
 */
final class AvatarGroup extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    /** @var list<Avatar> */
    private array $avatars;

    private ?int $max = null;

    private function __construct(Avatar ...$avatars)
    {
        parent::__construct();
        $this->avatars = array_values($avatars);
    }

    public static function make(Avatar ...$avatars): self
    {
        return new self(...$avatars);
    }

    public function max(int $max): self
    {
        $this->max = max(1, $max);

        return $this;
    }

    public function childrenForTree(): array
    {
        return $this->visibleAvatars();
    }

    /** @return list<Avatar> */
    private function visibleAvatars(): array
    {
        return $this->max !== null ? array_slice($this->avatars, 0, $this->max) : $this->avatars;
    }

    public function render(): string
    {
        $visible = $this->visibleAvatars();
        $itemClasses = $this->theme()->classes('admin.avatarGroup.item');

        $html = '';

        foreach ($visible as $avatar) {
            $html .= sprintf(
                '<span%s>%s</span>',
                $this->buildStructuralAttributeString($itemClasses),
                $this->renderChild($avatar),
            );
        }

        $remaining = count($this->avatars) - count($visible);

        if ($remaining > 0) {
            $html .= sprintf(
                '<span%s aria-hidden="true">+%d</span>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.avatarGroup.overflow')),
                $remaining,
            );
        }

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.avatarGroup'));

        return sprintf('<div%s>%s</div>', $attrs, $html);
    }
}
