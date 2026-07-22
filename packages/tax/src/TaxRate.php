<?php

declare(strict_types=1);

namespace Serixa\Tax;

use Serixa\Exception\InvalidArgumentException;

/**
 * Tax rate in basis points (10000 = 100%).
 */
final class TaxRate
{
    public function __construct(
        private readonly string $code,
        private readonly string $name,
        private readonly int $basisPoints,
        private readonly ?string $jurisdiction = null,
    ) {
        if ($code === '' || $name === '') {
            throw new InvalidArgumentException('Tax rate code and name are required.');
        }
        if ($basisPoints < 0) {
            throw new InvalidArgumentException('Tax rate cannot be negative.');
        }
    }

    public static function make(string $code, string $name, int $basisPoints, ?string $jurisdiction = null): self
    {
        return new self($code, $name, $basisPoints, $jurisdiction);
    }

    public static function percent(string $code, string $name, float $percent, ?string $jurisdiction = null): self
    {
        return new self($code, $name, (int) round($percent * 100), $jurisdiction);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function basisPoints(): int
    {
        return $this->basisPoints;
    }

    public function jurisdiction(): ?string
    {
        return $this->jurisdiction;
    }

    public function apply(int $amount): int
    {
        return (int) intdiv($amount * $this->basisPoints, 10000);
    }
}
