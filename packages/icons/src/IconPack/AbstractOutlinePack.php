<?php

declare(strict_types=1);

namespace Serixa\Icons\IconPack;

use Serixa\Icons\Contracts\IconPackInterface;
use Serixa\Icons\Exceptions\UnknownIconException;
use Serixa\Icons\Support\OutlineCatalog;

/**
 * Shared outline-catalog pack with optional name aliases.
 */
abstract class AbstractOutlinePack implements IconPackInterface
{
    /**
     * @return array<string, string> alias => catalog name
     */
    abstract protected function aliases(): array;

    public function icons(): array
    {
        $names = OutlineCatalog::names();
        $aliases = array_keys($this->aliases());
        $all = array_values(array_unique(array_merge($names, $aliases)));
        sort($all);

        return $all;
    }

    public function has(string $name): bool
    {
        return OutlineCatalog::has($this->canonical($name));
    }

    public function viewBox(string $name): string
    {
        $this->assertKnown($name);

        return OutlineCatalog::VIEW_BOX;
    }

    public function paths(string $name): array
    {
        return OutlineCatalog::paths($this->canonical($name));
    }

    public function defaultStrokeWidth(): float
    {
        return 1.5;
    }

    protected function canonical(string $name): string
    {
        $aliases = $this->aliases();

        return $aliases[$name] ?? $name;
    }

    protected function assertKnown(string $name): void
    {
        if (! $this->has($name)) {
            throw new UnknownIconException(sprintf(
                'Unknown icon "%s" in pack "%s".',
                $name,
                $this->name(),
            ));
        }
    }
}
