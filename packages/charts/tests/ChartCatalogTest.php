<?php

declare(strict_types=1);

namespace Serixa\Charts\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\Charts\Component\AreaChart;
use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Component\DonutChart;
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Component\PieChart;
use Serixa\Charts\Component\Sparkline;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Contracts\WidgetInterface;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\ThemeManager;

final class ChartCatalogTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        ThemeManager::reset();
        IconRegistry::reset();
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        parent::tearDown();
    }

    /**
     * @return array<string, array{0: callable(): WidgetInterface, 1: string}>
     */
    public static function chartProvider(): array
    {
        $series = static fn (): Dataset => Dataset::make()->addSeries(Series::make('Values')->values([1, 2, 3]));

        return [
            'line' => [static fn () => LineChart::make()->labels(['A', 'B', 'C'])->dataset($series())->summary('Line'), 'line'],
            'bar' => [static fn () => BarChart::make()->labels(['A', 'B', 'C'])->dataset($series())->summary('Bar'), 'bar'],
            'area' => [static fn () => AreaChart::make()->labels(['A', 'B', 'C'])->dataset($series())->summary('Area'), 'area'],
            'pie' => [static fn () => PieChart::make()->labels(['A', 'B', 'C'])->dataset($series())->summary('Pie'), 'pie'],
            'donut' => [static fn () => DonutChart::make()->labels(['A', 'B', 'C'])->dataset($series())->summary('Donut'), 'donut'],
            'sparkline' => [static fn () => Sparkline::make()->dataset($series())->summary('Spark'), 'sparkline'],
        ];
    }

    /**
     * @param callable(): WidgetInterface $factory
     */
    #[DataProvider('chartProvider')]
    public function testChartRendersAccessibleShell(callable $factory, string $type): void
    {
        $html = $this->renderer->renderWidget($factory());
        $this->assertStringContainsString('data-serixa-chart="1"', $html);
        $this->assertStringContainsString('data-serixa-chart-type="' . $type . '"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertTrue(str_contains($html, '<svg') || str_contains($html, '<table'));
    }
}
