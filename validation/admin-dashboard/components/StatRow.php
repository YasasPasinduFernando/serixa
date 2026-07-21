<?php

declare(strict_types=1);

namespace App\Components;

use Serixa\Component\Component;
use Serixa\Component\Concerns\ResolvesTheme;
use Serixa\Component\Row;
use Serixa\Component\StatCard;

/**
 * Horizontal row of StatCard widgets.
 */
final class StatRow extends Component
{
    use ResolvesTheme;

    /** @var list<StatCard> */
    private array $cards = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(StatCard ...$cards): self
    {
        $row = new self();
        $row->cards = array_values($cards);

        return $row;
    }

    public function cards(StatCard ...$cards): self
    {
        $this->cards = array_values($cards);

        return $this;
    }

    public function childrenForTree(): array
    {
        return $this->cards;
    }

    public function render(): string
    {
        return $this->renderChild(
            Row::make($this->cards)->gap(4)->spaceBetween(),
        );
    }
}
