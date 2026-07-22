<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Contracts\PanelInterface;

/**
 * Registry of DevTools overlay panels keyed by name.
 */
final class PanelRegistry
{
    /** @var array<string, PanelInterface> */
    private array $panels = [];

    public function register(PanelInterface $panel): void
    {
        $this->panels[$panel->name()] = $panel;
    }

    public function get(string $name): ?PanelInterface
    {
        return $this->panels[$name] ?? null;
    }

    /**
     * @return array<string, PanelInterface>
     */
    public function all(): array
    {
        return $this->panels;
    }
}
