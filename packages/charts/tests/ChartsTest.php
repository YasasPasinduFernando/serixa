<?php

declare(strict_types=1);

namespace Serixa\Charts\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Charts\Component\AreaChart;
use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Component\BubbleChart;
use Serixa\Charts\Component\DonutChart;
use Serixa\Charts\Component\Gauge;
use Serixa\Charts\Component\Heatmap;
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Component\PieChart;
use Serixa\Charts\Component\PolarChart;
use Serixa\Charts\Component\ProgressRing;
use Serixa\Charts\Component\RadarChart;
use Serixa\Charts\Component\ScatterChart;
use Serixa\Charts\Component\Sparkline;
use Serixa\Charts\Component\Timeline;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Normalizer;
use Serixa\Charts\Dataset\Series;
use Serixa\Charts\Runtime\ChartRuntime;
use Serixa\Charts\Theme\ChartsTheme;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class ChartsTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
    }

    public function testNormalizerAcceptsArraysJsonAndTraversable(): void
    {
        $this->assertSame([1.0, 2.5, 3.0], Normalizer::numbers([1, '2.5', 3]));
        $this->assertSame(['a', 'b'], Normalizer::labels(['a', 'b']));
        $this->assertSame([10.0, 20.0], Normalizer::numbers('[10,20]'));

        $gen = static function (): \Generator {
            yield 4;
            yield 5;
        };
        $this->assertSame([4.0, 5.0], Normalizer::numbers($gen()));
    }

    public function testDatasetSeriesToArray(): void
    {
        $dataset = Dataset::make()
            ->labels(['Jan', 'Feb', 'Mar'])
            ->addSeries(Series::make('Revenue')->values([10, 20, 15]));

        $payload = $dataset->toArray();
        $this->assertSame(['Jan', 'Feb', 'Mar'], $payload['labels']);
        $this->assertCount(1, $payload['series']);
        $this->assertSame('Revenue', $payload['series'][0]['name']);
        $this->assertSame([10.0, 20.0, 15.0], $payload['series'][0]['values']);
    }

    public function testLineChartRendersSvgTableAndEnhancementAttrs(): void
    {
        $html = $this->renderer->renderWidget(
            LineChart::make()
                ->title('Revenue')
                ->labels(['Jan', 'Feb', 'Mar'])
                ->dataset(
                    Dataset::make()->addSeries(Series::make('Sales')->values([12, 18, 9])),
                )
                ->smooth()
                ->legend()
                ->summary('Monthly revenue line chart'),
        );

        $this->assertStringContainsString('<figure', $html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('data-serixa-chart="1"', $html);
        $this->assertStringContainsString('data-serixa-chart-type="line"', $html);
        $this->assertStringContainsString('data-serixa-chart-config=', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertStringContainsString('Monthly revenue line chart', $html);
        $this->assertStringContainsString('Revenue', $html);
        $this->assertStringContainsString('<th', $html);
        $this->assertStringContainsString('Sales', $html);
    }

    public function testBarAreaPieDonutRender(): void
    {
        $bar = $this->renderer->renderWidget(
            BarChart::make()->labels(['A', 'B'])->dataset(
                Dataset::make()->addSeries(Series::make('Hits')->values([3, 7])),
            ),
        );
        $this->assertStringContainsString('data-serixa-chart-type="bar"', $bar);
        $this->assertStringContainsString('<svg', $bar);

        $area = $this->renderer->renderWidget(
            AreaChart::make()->labels(['A', 'B'])->dataset(
                Dataset::make()->addSeries(Series::make('Hits')->values([3, 7])),
            )->smooth(),
        );
        $this->assertStringContainsString('data-serixa-chart-type="area"', $area);

        $pie = $this->renderer->renderWidget(
            PieChart::make()->labels(['A', 'B'])->dataset(
                Dataset::make()->addSeries(Series::make('Share')->values([40, 60])),
            ),
        );
        $this->assertStringContainsString('data-serixa-chart-type="pie"', $pie);

        $donut = $this->renderer->renderWidget(
            DonutChart::make()->labels(['A', 'B'])->dataset(
                Dataset::make()->addSeries(Series::make('Share')->values([40, 60])),
            ),
        );
        $this->assertStringContainsString('data-serixa-chart-type="donut"', $donut);
    }

    public function testSpecializedChartsRender(): void
    {
        $cases = [
            Sparkline::make()->labels([1, 2, 3])->dataset(Dataset::make()->addSeries(Series::make('s')->values([1, 4, 2]))),
            ScatterChart::make()->labels(['a', 'b'])->dataset(Dataset::make()->addSeries(Series::make('s')->values([1, 2]))),
            BubbleChart::make()->labels(['a', 'b'])->dataset(Dataset::make()->addSeries(Series::make('s')->values([1, 2]))),
            RadarChart::make()->labels(['Speed', 'Power'])->dataset(Dataset::make()->addSeries(Series::make('A')->values([3, 4]))),
            PolarChart::make()->labels(['N', 'E'])->dataset(Dataset::make()->addSeries(Series::make('A')->values([2, 5]))),
            ProgressRing::make(72),
            Gauge::make(55),
            Timeline::make()->labels(['Kickoff', 'Ship'])->dataset(Dataset::make()->addSeries(Series::make('Events')->values([1, 2]))),
            Heatmap::make()->labels(['Mon', 'Tue'])->dataset(
                Dataset::make()
                    ->addSeries(Series::make('AM')->values([1, 3]))
                    ->addSeries(Series::make('PM')->values([2, 4])),
            ),
        ];

        foreach ($cases as $chart) {
            $html = $this->renderer->renderWidget($chart);
            $this->assertStringContainsString('<figure', $html);
            $this->assertStringContainsString('<svg', $html);
            $this->assertStringContainsString('data-serixa-chart="1"', $html);
        }
    }

    public function testChartsThemeResolvesKeys(): void
    {
        $theme = new ChartsTheme(new DefaultTheme());

        foreach ([
            'charts.root',
            'charts.figure',
            'charts.svg',
            'charts.table',
            'charts.legend',
            'charts.axis',
            'charts.grid',
            'charts.title',
            'charts.caption',
        ] as $key) {
            $this->assertNotEmpty($theme->classes($key), $key);
        }

        $this->assertNotEmpty($theme->classes('button'));
    }

    public function testChartRuntimeScriptExists(): void
    {
        $path = ChartRuntime::scriptPath();
        $this->assertFileExists($path);
        $contents = (string) file_get_contents($path);
        $this->assertStringNotContainsString('<?php', $contents);
        $this->assertStringContainsString("Serixa.module('chart'", $contents);
    }

    public function testTableFallbackCanBeDisabled(): void
    {
        $html = $this->renderer->renderWidget(
            LineChart::make()
                ->labels(['A'])
                ->dataset(Dataset::make()->addSeries(Series::make('S')->values([1])))
                ->tableFallback(false),
        );

        $this->assertStringNotContainsString('<table', $html);
        $this->assertStringContainsString('<svg', $html);
    }
}
