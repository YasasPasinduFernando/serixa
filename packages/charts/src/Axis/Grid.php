<?php

declare(strict_types=1);

namespace Serixa\Charts\Axis;

/**
 * Plot-area grid visibility.
 */
final class Grid
{
    private bool $show = true;

    private function __construct()
    {
    }

    public static function make(bool $show = true): self
    {
        $grid = new self();
        $grid->show = $show;

        return $grid;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    public function isShown(): bool
    {
        return $this->show;
    }

    /**
     * @return array{show: bool}
     */
    public function toArray(): array
    {
        return ['show' => $this->show];
    }
}
