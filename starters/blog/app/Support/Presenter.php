<?php

declare(strict_types=1);

namespace App\Support;

use Serixa\Admin\Components\StatusBadge;

/**
 * Small mapping helpers from fixture status strings to {@see StatusBadge}
 * instances, shared across the posts, dashboard and comments pages.
 */
final class Presenter
{
    private function __construct()
    {
    }

    public static function postStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'published' => StatusBadge::make('Published')->status('success'),
            'scheduled' => StatusBadge::make('Scheduled')->status('info'),
            'draft' => StatusBadge::make('Draft')->status('neutral'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function commentStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'approved' => StatusBadge::make('Approved')->status('success'),
            'pending' => StatusBadge::make('Pending')->status('warning'),
            'spam' => StatusBadge::make('Spam')->status('danger'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }
}
