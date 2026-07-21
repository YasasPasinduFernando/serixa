<?php

declare(strict_types=1);

namespace Serixa\Admin\Cards;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\AssertsAdminOption;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Icons\Component\Icon;

/**
 * Dashboard KPI card: title, big value, optional trend and icon.
 *
 * @example MetricCard::make()->title('Active users')->value('12,480')->trend('+4.2%', 'up')->icon('users')->color('primary')
 */
final class MetricCard extends Component implements AdminWidgetInterface
{
    use AssertsAdminOption;
    use ResolvesAdminTheme;

    private ?string $title = null;

    private ?string $value = null;

    private ?string $trend = null;

    private string $trendDirection = 'neutral';

    private ?Icon $iconWidget = null;

    private string $color = 'default';

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function value(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function trend(string $trend, string $direction = 'neutral'): self
    {
        $this->assertOption($direction, ['up', 'down', 'neutral'], 'trend direction');
        $this->trend = $trend;
        $this->trendDirection = $direction;

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->iconWidget = Icon::make($icon)->size('lg');

        return $this;
    }

    public function color(string $color): self
    {
        $this->assertOption($color, ['default', 'primary', 'success', 'warning', 'danger'], 'color');
        $this->color = $color;

        return $this;
    }

    public function childrenForTree(): array
    {
        return $this->iconWidget !== null ? [$this->iconWidget] : [];
    }

    public function render(): string
    {
        $textHtml = '';

        if ($this->title !== null) {
            $textHtml .= sprintf(
                '<p%s>%s</p>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.metricCard.title')),
                $this->escape($this->title),
            );
        }

        if ($this->value !== null) {
            $textHtml .= sprintf(
                '<p%s>%s</p>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.metricCard.value')),
                $this->escape($this->value),
            );
        }

        $iconHtml = '';

        if ($this->iconWidget !== null) {
            $iconHtml = sprintf(
                '<div%s aria-hidden="true">%s</div>',
                $this->buildStructuralAttributeString(
                    $this->theme()->classes('admin.metricCard.icon', ['color' => $this->color]),
                ),
                $this->renderChild($this->iconWidget),
            );
        }

        $header = sprintf(
            '<div%s><div>%s</div>%s</div>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.metricCard.header')),
            $textHtml,
            $iconHtml,
        );

        $trendHtml = '';

        if ($this->trend !== null) {
            $trendHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString(
                    $this->theme()->classes('admin.metricCard.trend', ['direction' => $this->trendDirection]),
                ),
                $this->escape($this->trend),
            );
        }

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.metricCard'));

        return sprintf('<div%s>%s%s</div>', $attrs, $header, $trendHtml);
    }
}
