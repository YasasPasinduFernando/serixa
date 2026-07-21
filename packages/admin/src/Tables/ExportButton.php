<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Toolbar\ToolbarAction;

/**
 * {@see ToolbarAction} preset for exporting table data.
 *
 * @example TableToolbar::make()->actions(ExportButton::make())
 */
final class ExportButton
{
    private function __construct()
    {
    }

    public static function make(string $label = 'Export'): ToolbarAction
    {
        return ToolbarAction::make($label)->icon('cloud-arrow-down');
    }
}
