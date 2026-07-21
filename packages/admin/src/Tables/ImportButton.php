<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Toolbar\ToolbarAction;

/**
 * {@see ToolbarAction} preset for importing table data.
 *
 * @example TableToolbar::make()->actions(ImportButton::make())
 */
final class ImportButton
{
    private function __construct()
    {
    }

    public static function make(string $label = 'Import'): ToolbarAction
    {
        return ToolbarAction::make($label)->icon('cloud-arrow-up');
    }
}
