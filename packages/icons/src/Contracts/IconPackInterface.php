<?php

declare(strict_types=1);

namespace Serixa\Icons\Contracts;

/**
 * Pluggable icon pack — path catalogs registered via {@see \Serixa\Icons\Registry\IconRegistry}.
 */
interface IconPackInterface
{
    public function name(): string;

    /**
     * @return list<string>
     */
    public function icons(): array;

    public function has(string $name): bool;

    /**
     * Canonical SVG viewBox (typically "0 0 24 24").
     */
    public function viewBox(string $name): string;

    /**
     * @return list<array{d: string, fill_rule?: string}>
     */
    public function paths(string $name): array;

    /**
     * Default stroke width for outlined icons in this pack.
     */
    public function defaultStrokeWidth(): float;
}
