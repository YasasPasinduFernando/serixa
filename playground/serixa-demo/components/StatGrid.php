<?php

declare(strict_types=1);

namespace SerixaDemo\Components;

use Serixa\Component\Row;
use Serixa\Component\StatCard;
use Serixa\Contracts\WidgetInterface;

/**
 * Horizontal row of StatCard widgets (demo-only helper).
 */
final class StatGrid
{
    /**
     * @param array{
     *     title: string,
     *     value: string,
     *     subtitle?: string,
     *     trend?: string,
     *     color?: string
     * } ...$stats
     */
    public static function make(array ...$stats): WidgetInterface
    {
        $cards = [];

        foreach ($stats as $stat) {
            $card = StatCard::make()
                ->title($stat['title'])
                ->value($stat['value']);

            if (isset($stat['subtitle'])) {
                $card->subtitle($stat['subtitle']);
            }

            if (isset($stat['trend'])) {
                $card->trend($stat['trend']);
            }

            if (isset($stat['color'])) {
                $card->color($stat['color']);
            }

            $cards[] = $card;
        }

        return Row::make($cards)->gap(4)->spaceBetween();
    }
}
