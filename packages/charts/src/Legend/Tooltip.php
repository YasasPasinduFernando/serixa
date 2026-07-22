<?php

declare(strict_types=1);

namespace Serixa\Charts\Legend;

/**
 * Tooltip enabled flag — SSR hint; client JS uses the same config.
 */
final class Tooltip
{
    private bool $enabled = true;

    private function __construct()
    {
    }

    public static function make(bool $enabled = true): self
    {
        $tooltip = new self();
        $tooltip->enabled = $enabled;

        return $tooltip;
    }

    public function enabled(bool $enabled = true): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return array{enabled: bool}
     */
    public function toArray(): array
    {
        return ['enabled' => $this->enabled];
    }
}
