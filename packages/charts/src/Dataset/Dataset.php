<?php

declare(strict_types=1);

namespace Serixa\Charts\Dataset;

/**
 * Chart dataset: shared labels plus one or more named series.
 */
final class Dataset implements Arrayable
{
    /** @var list<string> */
    private array $labels = [];

    /** @var list<Series> */
    private array $series = [];

    private function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * @param iterable<mixed> $labels
     */
    public function labels(iterable $labels): self
    {
        $this->labels = Normalizer::labels($labels);

        return $this;
    }

    public function series(Series ...$series): self
    {
        foreach ($series as $item) {
            $this->series[] = $item;
        }

        return $this;
    }

    public function addSeries(Series $series): self
    {
        $this->series[] = $series;

        return $this;
    }

    /**
     * @return list<string>
     */
    public function labelList(): array
    {
        return $this->labels;
    }

    /**
     * @return list<Series>
     */
    public function seriesList(): array
    {
        return $this->series;
    }

    /**
     * @return array{labels: list<string>, series: list<array{name: string, values: list<mixed>, color: ?string}>}
     */
    public function toArray(): array
    {
        return [
            'labels' => $this->labels,
            'series' => array_map(
                static fn (Series $series): array => $series->toArray(),
                $this->series,
            ),
        ];
    }
}
