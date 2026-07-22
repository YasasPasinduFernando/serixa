<?php

declare(strict_types=1);

namespace Serixa\Checkout;

use Serixa\Exception\InvalidArgumentException;

final class Address
{
    public function __construct(
        private readonly string $name,
        private readonly string $line1,
        private readonly string $city,
        private readonly string $country,
        private readonly ?string $line2 = null,
        private readonly ?string $postalCode = null,
        private readonly ?string $region = null,
    ) {
        if ($name === '' || $line1 === '' || $city === '' || $country === '') {
            throw new InvalidArgumentException('Address requires name, line1, city, and country.');
        }
    }

    public static function make(
        string $name,
        string $line1,
        string $city,
        string $country,
        ?string $line2 = null,
        ?string $postalCode = null,
        ?string $region = null,
    ): self {
        return new self($name, $line1, $city, strtoupper($country), $line2, $postalCode, $region);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function line1(): string
    {
        return $this->line1;
    }

    public function line2(): ?string
    {
        return $this->line2;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function postalCode(): ?string
    {
        return $this->postalCode;
    }

    public function region(): ?string
    {
        return $this->region;
    }
}
