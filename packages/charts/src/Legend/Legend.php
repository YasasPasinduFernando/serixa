<?php

declare(strict_types=1);

namespace Serixa\Charts\Legend;

use Serixa\Charts\Exceptions\ChartsException;

/**
 * Chart legend placement.
 */
final class Legend
{
    public const TOP = 'top';

    public const BOTTOM = 'bottom';

    public const LEFT = 'left';

    public const RIGHT = 'right';

    private string $position = self::BOTTOM;

    private bool $visible = true;

    private function __construct()
    {
    }

    public static function make(string $position = self::BOTTOM): self
    {
        return (new self())->position($position);
    }

    public function position(string $position): self
    {
        $allowed = [self::TOP, self::BOTTOM, self::LEFT, self::RIGHT];

        if (! in_array($position, $allowed, true)) {
            throw new ChartsException(sprintf(
                'Invalid legend position "%s". Expected one of: %s.',
                $position,
                implode(', ', $allowed),
            ));
        }

        $this->position = $position;

        return $this;
    }

    public function visible(bool $visible = true): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function positionValue(): string
    {
        return $this->position;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @return array{position: string, visible: bool}
     */
    public function toArray(): array
    {
        return [
            'position' => $this->position,
            'visible' => $this->visible,
        ];
    }
}
