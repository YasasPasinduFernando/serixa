<?php

declare(strict_types=1);

namespace Serixa\Icons\IconPack;

/**
 * Lucide-oriented aliases over the shared outline catalog.
 */
final class Lucide extends AbstractOutlinePack
{
    public function name(): string
    {
        return 'lucide';
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
            'trash-2' => 'trash',
            'triangle-alert' => 'exclamation-triangle',
            'circle-alert' => 'exclamation-circle',
            'info' => 'information-circle',
            'circle-help' => 'question-mark-circle',
            'circle-check' => 'check-circle',
            'circle-x' => 'x-circle',
            'ellipsis' => 'ellipsis-horizontal',
            'ellipsis-vertical' => 'ellipsis-vertical',
            'image' => 'photo',
            'shopping-cart' => 'shopping-cart',
            'badge-dollar-sign' => 'currency-dollar',
            'map-pin' => 'map-pin',
            'message-circle' => 'chat-bubble-left',
            'thumbs-up' => 'hand-thumb-up',
            'thumbs-down' => 'hand-thumb-down',
            'external-link' => 'open',
            'refresh-cw' => 'arrow-path',
            'sliders-horizontal' => 'adjustments-horizontal',
            'house' => 'home',
            'panel-left' => 'bars-3',
            'filter' => 'funnels',
        ];
    }
}
