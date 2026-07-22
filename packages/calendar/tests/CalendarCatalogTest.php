<?php

declare(strict_types=1);

namespace Serixa\Calendar\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\Calendar\Component\Calendar;
use Serixa\Calendar\Component\DayView;
use Serixa\Calendar\Component\MonthView;
use Serixa\Calendar\Component\WeekView;
use Serixa\Calendar\Event\Event;
use Serixa\Calendar\Theme\CalendarTheme;
use Serixa\Contracts\WidgetInterface;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class CalendarCatalogTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        ThemeManager::reset();
        IconRegistry::reset();
        ThemeManager::set(new CalendarTheme(new DefaultTheme()));
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        parent::tearDown();
    }

    /**
     * @return array<string, array{0: callable(): WidgetInterface, 1: list<string>}>
     */
    public static function viewProvider(): array
    {
        $events = [Event::make('Ship')->start('2026-07-22')->allDay()];

        return [
            'calendar' => [
                static fn () => Calendar::make()->view('month')->focus('2026-07-01')->today('2026-07-22')->events($events)->summary('Shell'),
                ['data-serixa-calendar="1"', 'aria-live="polite"'],
            ],
            'month' => [
                static fn () => MonthView::make()->focus('2026-07-01')->today('2026-07-22')->events($events)->summary('Month'),
                ['role="grid"', 'data-serixa-calendar-kind="month"'],
            ],
            'week' => [
                static fn () => WeekView::make()->focus('2026-07-20')->today('2026-07-22')->events($events)->summary('Week'),
                ['data-serixa-calendar="1"', 'aria-live="polite"'],
            ],
            'day' => [
                static fn () => DayView::make()->focus('2026-07-22')->today('2026-07-22')->events($events)->summary('Day'),
                ['data-serixa-calendar="1"', 'aria-live="polite"'],
            ],
        ];
    }

    /**
     * @param callable(): WidgetInterface $factory
     * @param list<string> $needles
     */
    #[DataProvider('viewProvider')]
    public function testCalendarViewsRender(callable $factory, array $needles): void
    {
        $html = $this->renderer->renderWidget($factory());
        foreach ($needles as $needle) {
            $this->assertStringContainsString($needle, $html);
        }
    }
}
