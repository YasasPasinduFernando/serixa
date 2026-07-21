<?php

declare(strict_types=1);

namespace Serixa\Admin\Components;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\AssertsAdminOption;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Semantic status pill (neutral/info/success/warning/danger).
 *
 * @example StatusBadge::make('Active')->status('success')
 */
final class StatusBadge extends Component implements AdminWidgetInterface
{
    use AssertsAdminOption;
    use ResolvesAdminTheme;

    private string $label;

    private string $status = 'neutral';

    private function __construct(string $label)
    {
        parent::__construct();
        $this->label = $label;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function status(string $status): self
    {
        $this->assertOption($status, ['neutral', 'info', 'success', 'warning', 'danger'], 'status');
        $this->status = $status;

        return $this;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('admin.statusBadge', ['status' => $this->status]),
        );

        return sprintf('<span%s>%s</span>', $attrs, $this->escape($this->label));
    }
}
