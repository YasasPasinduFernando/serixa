<?php

declare(strict_types=1);

namespace Serixa\Icons\IconPack;

/**
 * Feather-oriented aliases over the shared outline catalog.
 */
final class Feather extends AbstractOutlinePack
{
    public function name(): string
    {
        return 'feather';
    }

    public function defaultStrokeWidth(): float
    {
        return 2.0;
    }

    protected function aliases(): array
    {
        return [
            'menu' => 'bars-3',
            'x' => 'x-mark',
            'search' => 'magnifying-glass',
            'mail' => 'envelope',
            'settings' => 'cog',
            'log-out' => 'arrow-right-on-rectangle',
            'log-in' => 'arrow-left-on-rectangle',
            'edit-2' => 'pencil',
            'edit-3' => 'pencil-square',
            'trash-2' => 'trash',
            'alert-triangle' => 'exclamation-triangle',
            'alert-circle' => 'exclamation-circle',
            'info' => 'information-circle',
            'help-circle' => 'question-mark-circle',
            'check-circle' => 'check-circle',
            'x-circle' => 'x-circle',
            'chevron-left' => 'chevron-left',
            'chevron-right' => 'chevron-right',
            'more-horizontal' => 'ellipsis-horizontal',
            'more-vertical' => 'ellipsis-vertical',
            'image' => 'photo',
            'shopping-cart' => 'shopping-cart',
            'dollar-sign' => 'currency-dollar',
            'map-pin' => 'map-pin',
            'message-circle' => 'chat-bubble-left',
            'thumbs-up' => 'hand-thumb-up',
            'thumbs-down' => 'hand-thumb-down',
            'external-link' => 'open',
            'refresh-cw' => 'arrow-path',
            'sliders' => 'adjustments-horizontal',
            'filter' => 'funnels',
            'globe' => 'globe',
        ];
    }
}
