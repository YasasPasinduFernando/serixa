<?php

declare(strict_types=1);

namespace App\Support;

use Serixa\Admin\Components\StatusBadge;

/**
 * Maps fixture status strings to {@see StatusBadge} instances shared across
 * list pages and the dashboard.
 */
final class Presenter
{
    private function __construct()
    {
    }

    public static function userStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'active' => StatusBadge::make('Active')->status('success'),
            'invited' => StatusBadge::make('Invited')->status('warning'),
            'suspended' => StatusBadge::make('Suspended')->status('neutral'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function orderStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'paid' => StatusBadge::make('Paid')->status('success'),
            'fulfilled' => StatusBadge::make('Fulfilled')->status('success'),
            'processing' => StatusBadge::make('Processing')->status('info'),
            'pending' => StatusBadge::make('Pending')->status('warning'),
            'cancelled' => StatusBadge::make('Cancelled')->status('danger'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function invoiceStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'paid' => StatusBadge::make('Paid')->status('success'),
            'open' => StatusBadge::make('Open')->status('info'),
            'overdue' => StatusBadge::make('Overdue')->status('danger'),
            'draft' => StatusBadge::make('Draft')->status('neutral'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function productStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'active' => StatusBadge::make('Active')->status('success'),
            'draft' => StatusBadge::make('Draft')->status('neutral'),
            'archived' => StatusBadge::make('Archived')->status('warning'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function reportStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'ready' => StatusBadge::make('Ready')->status('success'),
            'processing' => StatusBadge::make('Processing')->status('warning'),
            'failed' => StatusBadge::make('Failed')->status('danger'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function notificationStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'unread' => StatusBadge::make('Unread')->status('info'),
            'read' => StatusBadge::make('Read')->status('neutral'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function auditSeverityBadge(string $severity): StatusBadge
    {
        return match ($severity) {
            'info' => StatusBadge::make('Info')->status('info'),
            'warning' => StatusBadge::make('Warning')->status('warning'),
            'critical' => StatusBadge::make('Critical')->status('danger'),
            default => StatusBadge::make(ucfirst($severity))->status('neutral'),
        };
    }
}
