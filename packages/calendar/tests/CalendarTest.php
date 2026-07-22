<?php

declare(strict_types=1);

namespace Serixa\Calendar\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Calendar\Component\AgendaView;
use Serixa\Calendar\Component\Calendar;
use Serixa\Calendar\Component\DatePicker;
use Serixa\Calendar\Component\DayView;
use Serixa\Calendar\Component\EventBadge;
use Serixa\Calendar\Component\EventCard;
use Serixa\Calendar\Component\MiniCalendar;
use Serixa\Calendar\Component\MonthView;
use Serixa\Calendar\Component\ResourceCalendar;
use Serixa\Calendar\Component\Scheduler;
use Serixa\Calendar\Component\TimelineCalendar;
use Serixa\Calendar\Component\WeekView;
use Serixa\Calendar\Event\Event;
use Serixa\Calendar\Event\EventCollection;
use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Runtime\CalendarRuntime;
use Serixa\Calendar\Theme\CalendarTheme;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class CalendarTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        ThemeManager::set(new CalendarTheme(new DefaultTheme()));
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
    }

    public function testEventNormalizerAcceptsArraysJsonAndGenerators(): void
    {
        $fromArray = EventNormalizer::normalize([
            ['title' => 'A', 'start' => '2026-07-22 10:00', 'end' => '2026-07-22 11:00'],
        ]);
        $this->assertCount(1, $fromArray);
        $this->assertSame('A', $fromArray[0]->getTitle());

        $fromJson = EventNormalizer::normalize('[{"title":"B","start":"2026-07-23"}]');
        $this->assertSame('B', $fromJson[0]->getTitle());

        $gen = static function (): \Generator {
            yield Event::make('C')->start('2026-07-24');
        };
        $this->assertSame('C', EventNormalizer::normalize($gen())[0]->getTitle());
    }

    public function testEventCollectionToArray(): void
    {
        $collection = EventCollection::make([
            Event::make('Meet')->start('2026-07-22 09:00')->end('2026-07-22 09:30')->color('#fff'),
        ]);
        $payload = $collection->toArray();
        $this->assertSame('Meet', $payload[0]['title']);
        $this->assertStringContainsString('2026-07-22', $payload[0]['start']);
    }

    public function testMonthViewRendersGridAndEnhancementAttrs(): void
    {
        $html = $this->renderer->renderWidget(
            MonthView::make()
                ->focus('2026-07-01')
                ->today('2026-07-22')
                ->events([Event::make('Ship')->start('2026-07-22')->allDay()])
                ->summary('July month grid'),
        );

        $this->assertStringContainsString('role="grid"', $html);
        $this->assertStringContainsString('data-serixa-calendar="1"', $html);
        $this->assertStringContainsString('data-serixa-calendar-kind="month"', $html);
        $this->assertStringContainsString('data-serixa-calendar-day="2026-07-22"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertStringContainsString('July month grid', $html);
        $this->assertStringContainsString('Ship', $html);
    }

    public function testCalendarShellAndViewsRender(): void
    {
        $events = [Event::make('X')->start('2026-07-22 10:00')->end('2026-07-22 11:00')];

        foreach ([
            Calendar::make()->view('month')->focus('2026-07-01')->today('2026-07-22')->events($events),
            WeekView::make()->focus('2026-07-22')->today('2026-07-22')->events($events),
            DayView::make()->focus('2026-07-22')->today('2026-07-22')->events($events),
            AgendaView::make()->focus('2026-07-01')->today('2026-07-22')->events($events),
            MiniCalendar::make()->focus('2026-07-01')->today('2026-07-22')->selected('2026-07-22'),
            TimelineCalendar::make()->focus('2026-07-01')->events($events),
            ResourceCalendar::make()->focus('2026-07-22')->resources(['desk-1'])->events(
                [Event::make('Seat')->start('2026-07-22 09:00')->resource('desk-1')],
            ),
            Scheduler::make()->focus('2026-07-22')->mode('week')->events($events),
            DatePicker::make('d')->value('2026-07-22'),
        ] as $widget) {
            $html = $this->renderer->renderWidget($widget);
            $this->assertStringContainsString('data-serixa-calendar="1"', $html);
        }
    }

    public function testEventCardBadgeAndTheme(): void
    {
        $event = Event::make('Talk')->start('2026-07-22 15:00')->description('Hall B');
        $card = $this->renderer->renderWidget(EventCard::make($event));
        $this->assertStringContainsString('Talk', $card);
        $this->assertStringContainsString('<article', $card);

        $badge = $this->renderer->renderWidget(EventBadge::count(3));
        $this->assertStringContainsString('3 events', $badge);

        $theme = new CalendarTheme(new DefaultTheme());
        foreach (['calendar.root', 'calendar.grid', 'calendar.day', 'calendar.event', 'calendar.toolbar'] as $key) {
            $this->assertNotEmpty($theme->classes($key), $key);
        }
    }

    public function testCalendarRuntimeScriptExists(): void
    {
        $path = CalendarRuntime::scriptPath();
        $this->assertFileExists($path);
        $contents = (string) file_get_contents($path);
        $this->assertStringNotContainsString('<?php', $contents);
        $this->assertStringContainsString("Serixa.module('calendar'", $contents);
    }

    public function testAccessibilitySelectedOnMiniCalendar(): void
    {
        $html = $this->renderer->renderWidget(
            MiniCalendar::make()
                ->focus('2026-07-01')
                ->today('2026-07-22')
                ->selected('2026-07-22'),
        );

        $this->assertStringContainsString('aria-selected="true"', $html);
        $this->assertStringContainsString('role="grid"', $html);
    }
}
