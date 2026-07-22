<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Axis\Grid;
use Serixa\Charts\Axis\XAxis;
use Serixa\Charts\Axis\YAxis;
use Serixa\Charts\Contracts\ChartWidgetInterface;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Charts\Exceptions\ChartsException;
use Serixa\Charts\Legend\Annotation;
use Serixa\Charts\Legend\Legend;
use Serixa\Charts\Legend\Marker;
use Serixa\Charts\Legend\Threshold;
use Serixa\Charts\Legend\Tooltip;
use Serixa\Charts\Support\Html;
use Serixa\Charts\Support\ResolvesChartsTheme;
use Serixa\Charts\Support\Svg;
use Serixa\Component\Component;

/**
 * Abstract SSR chart widget: figure + SVG + table fallback + progressive-enhancement attrs.
 */
abstract class Chart extends Component implements ChartWidgetInterface
{
    use ResolvesChartsTheme;

    protected ?string $chartTitle = null;

    protected ?string $chartCaption = null;

    protected Dataset $dataset;

    protected ?Legend $legendConfig = null;

    protected bool $showLegend = true;

    protected ?Grid $gridConfig = null;

    protected bool $showGrid = true;

    protected ?XAxis $xAxisConfig = null;

    protected ?YAxis $yAxisConfig = null;

    protected int $chartWidth = 640;

    protected int $chartHeight = 360;

    protected ?string $ariaSummary = null;

    protected bool $tableFallback = true;

    protected bool $tableSrOnly = true;

    protected ?Tooltip $tooltipConfig = null;

    /** @var list<Marker> */
    protected array $markers = [];

    /** @var list<Threshold> */
    protected array $thresholds = [];

    /** @var list<Annotation> */
    protected array $annotations = [];

    protected function __construct()
    {
        parent::__construct();
        $this->dataset = Dataset::make();
        $this->tooltipConfig = Tooltip::make(true);
    }

    public function title(string $title): static
    {
        $this->chartTitle = $title;

        return $this;
    }

    public function caption(string $caption): static
    {
        $this->chartCaption = $caption;

        return $this;
    }

    /**
     * @param iterable<mixed> $labels
     */
    public function labels(iterable $labels): static
    {
        $this->dataset->labels($labels);

        return $this;
    }

    /**
     * @param Dataset|callable(Dataset): void $dataset
     */
    public function dataset(Dataset|callable $dataset): static
    {
        if ($dataset instanceof Dataset) {
            $this->dataset = $dataset;

            return $this;
        }

        $built = Dataset::make();
        $dataset($built);
        $this->dataset = $built;

        return $this;
    }

    public function series(Series ...$series): static
    {
        $this->dataset->series(...$series);

        return $this;
    }

    public function legend(bool|Legend $legend): static
    {
        if (is_bool($legend)) {
            $this->showLegend = $legend;

            return $this;
        }

        $this->showLegend = $legend->isVisible();
        $this->legendConfig = $legend;

        return $this;
    }

    public function grid(bool|Grid $grid): static
    {
        if (is_bool($grid)) {
            $this->showGrid = $grid;
            $this->gridConfig = Grid::make($grid);

            return $this;
        }

        $this->showGrid = $grid->isShown();
        $this->gridConfig = $grid;

        return $this;
    }

    public function xAxis(?XAxis $axis = null): static
    {
        $this->xAxisConfig = $axis ?? XAxis::make();

        return $this;
    }

    public function yAxis(?YAxis $axis = null): static
    {
        $this->yAxisConfig = $axis ?? YAxis::make();

        return $this;
    }

    public function width(int $width): static
    {
        if ($width < 1) {
            throw new ChartsException('Chart width must be a positive integer.');
        }

        $this->chartWidth = $width;

        return $this;
    }

    public function height(int $height): static
    {
        if ($height < 1) {
            throw new ChartsException('Chart height must be a positive integer.');
        }

        $this->chartHeight = $height;

        return $this;
    }

    public function summary(string $summary): static
    {
        $this->ariaSummary = $summary;

        return $this;
    }

    public function tableFallback(bool $enabled = true): static
    {
        $this->tableFallback = $enabled;

        return $this;
    }

    public function tableSrOnly(bool $srOnly = true): static
    {
        $this->tableSrOnly = $srOnly;

        return $this;
    }

    public function tooltip(bool|Tooltip $tooltip): static
    {
        $this->tooltipConfig = is_bool($tooltip) ? Tooltip::make($tooltip) : $tooltip;

        return $this;
    }

    public function marker(Marker ...$markers): static
    {
        foreach ($markers as $marker) {
            $this->markers[] = $marker;
        }

        return $this;
    }

    public function threshold(Threshold ...$thresholds): static
    {
        foreach ($thresholds as $threshold) {
            $this->thresholds[] = $threshold;
        }

        return $this;
    }

    public function annotation(Annotation ...$annotations): static
    {
        foreach ($annotations as $annotation) {
            $this->annotations[] = $annotation;
        }

        return $this;
    }

    abstract protected function buildSvg(): string;

    abstract protected function chartType(): string;

    /**
     * @return array<string, mixed>
     */
    protected function configPayload(): array
    {
        return [
            'type' => $this->chartType(),
            'width' => $this->chartWidth,
            'height' => $this->chartHeight,
            'dataset' => $this->dataset->toArray(),
            'legend' => $this->legendConfig?->toArray() ?? ['position' => Legend::BOTTOM, 'visible' => $this->showLegend],
            'grid' => $this->gridConfig?->toArray() ?? ['show' => $this->showGrid],
            'xAxis' => $this->xAxisConfig?->toArray(),
            'yAxis' => $this->yAxisConfig?->toArray(),
            'tooltip' => $this->tooltipConfig?->toArray() ?? ['enabled' => true],
            'markers' => array_map(static fn (Marker $m): array => $m->toArray(), $this->markers),
            'thresholds' => array_map(static fn (Threshold $t): array => $t->toArray(), $this->thresholds),
            'annotations' => array_map(static fn (Annotation $a): array => $a->toArray(), $this->annotations),
            'title' => $this->chartTitle,
            'caption' => $this->chartCaption,
        ];
    }

    protected function buildTable(): string
    {
        $labels = $this->dataset->labelList();
        $seriesList = $this->dataset->seriesList();

        if ($seriesList === []) {
            return '';
        }

        $theme = $this->theme();

        $headCells = Html::tag('th', ' scope="col"', Html::escape(''));

        foreach ($seriesList as $series) {
            $headCells .= Html::tag('th', ' scope="col"', Html::escape($series->name()));
        }

        $thead = Html::tag('thead', '', Html::tag('tr', '', $headCells));
        $bodyRows = '';

        $rowCount = max(
            count($labels),
            ...array_map(static fn (Series $s): int => count($s->numericValues()), $seriesList),
        );

        for ($i = 0; $i < $rowCount; $i++) {
            $cells = Html::tag('th', ' scope="row"', Html::escape($labels[$i] ?? (string) ($i + 1)));

            foreach ($seriesList as $series) {
                $values = $series->numericValues();
                $cells .= Html::tag('td', '', Html::escape(isset($values[$i]) ? (string) $values[$i] : ''));
            }

            $bodyRows .= Html::tag('tr', '', $cells);
        }

        $tbody = Html::tag('tbody', '', $bodyRows);

        return Html::tag(
            'table',
            $this->buildStructuralAttributeString($theme->classes('charts.table', ['srOnly' => $this->tableSrOnly])),
            $thead . $tbody,
        );
    }

    protected function resolvedGrid(): Grid
    {
        return $this->gridConfig ?? Grid::make($this->showGrid);
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $svgInner = $this->buildSvg();
        $svgClass = implode(' ', $theme->classes('charts.svg'));

        $svg = Svg::tag('svg', [
            'class' => $svgClass,
            'viewBox' => sprintf('0 0 %d %d', $this->chartWidth, $this->chartHeight),
            'width' => $this->chartWidth,
            'height' => $this->chartHeight,
            'role' => 'img',
            'aria-hidden' => $this->tableFallback ? 'true' : null,
            'focusable' => 'false',
        ], $svgInner);

        $parts = '';

        if ($this->chartTitle !== null && $this->chartTitle !== '') {
            $parts .= Html::tag(
                'div',
                $this->buildStructuralAttributeString($theme->classes('charts.title')),
                $this->escape($this->chartTitle),
            );
        }

        if ($this->chartCaption !== null && $this->chartCaption !== '') {
            $parts .= Html::tag(
                'figcaption',
                $this->buildStructuralAttributeString($theme->classes('charts.caption')),
                $this->escape($this->chartCaption),
            );
        }

        $parts .= $svg;

        if ($this->tableFallback) {
            $parts .= $this->buildTable();
        }

        // Live region for progressive enhancement tips (JS fills text).
        $parts .= Html::tag('div', ' data-serixa-chart-live="1" aria-live="polite" class="sr-only"', '');

        $ariaLabel = $this->ariaSummary
            ?? ($this->chartTitle !== null && $this->chartTitle !== ''
                ? $this->chartTitle
                : ucfirst($this->chartType()) . ' chart');

        $figureAttrs = $this->buildAttributeString(
            $theme->classes('charts.figure'),
            [
                'data-serixa-chart' => '1',
                'data-serixa-chart-type' => $this->chartType(),
                'data-serixa-chart-config' => Html::jsonAttr($this->configPayload()),
                'aria-label' => $ariaLabel,
                'role' => 'group',
            ],
        );

        $html = Html::tag('figure', $figureAttrs, $parts);

        $this->afterRender();

        return $html;
    }
}
