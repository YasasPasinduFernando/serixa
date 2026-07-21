<?php

declare(strict_types=1);

namespace Serixa\Admin\Cards;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Compact inline label/value pair, e.g. for card footers or dense summaries.
 *
 * @example Statistic::make('Open invoices', '12')
 */
final class Statistic extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $label;

    private string $value;

    private function __construct(string $label, string $value)
    {
        parent::__construct();
        $this->label = $label;
        $this->value = $value;
    }

    public static function make(string $label, string $value): self
    {
        return new self($label, $value);
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.statistic'));

        return sprintf(
            '<div%s><span%s>%s</span><span%s>%s</span></div>',
            $attrs,
            $this->buildStructuralAttributeString($this->theme()->classes('admin.statistic.label')),
            $this->escape($this->label),
            $this->buildStructuralAttributeString($this->theme()->classes('admin.statistic.value')),
            $this->escape($this->value),
        );
    }
}
