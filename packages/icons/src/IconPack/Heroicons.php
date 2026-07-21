<?php

declare(strict_types=1);

namespace Serixa\Icons\IconPack;

/**
 * Default SERIXA icon pack — Heroicons-compatible outline set.
 */
final class Heroicons extends AbstractOutlinePack
{
    public function name(): string
    {
        return 'heroicons';
    }

    protected function aliases(): array
    {
        return [
            'menu' => 'bars-3',
            'search' => 'magnifying-glass',
            'mail' => 'envelope',
            'settings' => 'cog-6-tooth',
            'logout' => 'arrow-right-on-rectangle',
            'login' => 'arrow-left-on-rectangle',
            'close' => 'x-mark',
            'edit' => 'pencil-square',
            'delete' => 'trash',
            'warning' => 'exclamation-triangle',
            'info' => 'information-circle',
            'success' => 'check-circle',
            'error' => 'x-circle',
        ];
    }
}
