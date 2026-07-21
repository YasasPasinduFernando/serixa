<?php

declare(strict_types=1);

namespace SerixaDemo;

use Serixa\Component\Card;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;

/**
 * Visible stand-in when SERIXA does not yet expose a required widget.
 * Demo-only — not part of the framework.
 */
final class Missing
{
    /** @var list<array{widget: string, purpose: string}> */
    private static array $log = [];

    /**
     * Record and render a missing-widget placeholder.
     */
    public static function widget(string $name, string $purpose): WidgetInterface
    {
        self::$log[] = ['widget' => $name, 'purpose' => $purpose];

        return Card::make(
            Text::make(sprintf('[Missing widget: %s]', $name))
                ->as('strong')
                ->size('sm')
                ->weight('semibold')
                ->tone('danger'),
            Text::make($purpose)
                ->size('sm')
                ->tone('muted'),
        )->padding('sm')->class('border border-dashed border-rose-300 bg-rose-50');
    }

    /**
     * @return list<array{widget: string, purpose: string}>
     */
    public static function recorded(): array
    {
        return self::$log;
    }

    public static function reset(): void
    {
        self::$log = [];
    }
}
