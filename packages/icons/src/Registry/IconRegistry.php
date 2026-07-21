<?php

declare(strict_types=1);

namespace Serixa\Icons\Registry;

use Serixa\Icons\Contracts\IconPackInterface;
use Serixa\Icons\Exceptions\UnknownIconException;
use Serixa\Icons\Exceptions\UnknownPackException;
use Serixa\Icons\IconPack\Feather;
use Serixa\Icons\IconPack\Heroicons;
use Serixa\Icons\IconPack\Lucide;

/**
 * Process-wide icon pack registry. Plugins register packs without touching framework code.
 */
final class IconRegistry
{
    private static ?self $instance = null;

    /** @var array<string, IconPackInterface> */
    private array $packs = [];

    private string $defaultPack = 'heroicons';

    public static function default(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance
                ->register(new Heroicons())
                ->register(new Feather())
                ->register(new Lucide());
        }

        return self::$instance;
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    public function register(IconPackInterface $pack): self
    {
        $this->packs[$pack->name()] = $pack;

        return $this;
    }

    public function remove(string $name): self
    {
        unset($this->packs[$name]);

        if ($this->defaultPack === $name) {
            $this->defaultPack = array_key_first($this->packs) ?? 'heroicons';
        }

        return $this;
    }

    public function defaultPack(string $name): self
    {
        if (! isset($this->packs[$name])) {
            throw new UnknownPackException(sprintf('Cannot set default pack "%s": pack is not registered.', $name));
        }

        $this->defaultPack = $name;

        return $this;
    }

    public function defaultPackName(): string
    {
        return $this->defaultPack;
    }

    public function pack(string $name): IconPackInterface
    {
        if (! isset($this->packs[$name])) {
            throw new UnknownPackException(sprintf(
                'Unknown icon pack "%s". Registered: %s.',
                $name,
                $this->packs === [] ? '(none)' : implode(', ', array_keys($this->packs)),
            ));
        }

        return $this->packs[$name];
    }

    public function has(string $icon, ?string $pack = null): bool
    {
        $packName = $pack ?? $this->defaultPack;

        if (! isset($this->packs[$packName])) {
            return false;
        }

        return $this->packs[$packName]->has($icon);
    }

    /**
     * @return array{pack: IconPackInterface, name: string, viewBox: string, paths: list<array{d: string, fill_rule?: string}>}
     */
    public function resolve(string $icon, ?string $pack = null): array
    {
        $packName = $pack ?? $this->defaultPack;
        $resolved = $this->pack($packName);

        if (! $resolved->has($icon)) {
            throw new UnknownIconException(sprintf(
                'Unknown icon "%s" in pack "%s". Register a pack via IconRegistry or use a known name.',
                $icon,
                $packName,
            ));
        }

        return [
            'pack' => $resolved,
            'name' => $icon,
            'viewBox' => $resolved->viewBox($icon),
            'paths' => $resolved->paths($icon),
        ];
    }

    /**
     * @return list<string>
     */
    public function packNames(): array
    {
        $names = array_keys($this->packs);
        sort($names);

        return $names;
    }
}
