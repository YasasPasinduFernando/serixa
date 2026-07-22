<?php

declare(strict_types=1);

namespace App\Support;

use Serixa\Admin\Components\StatusBadge;

/** Maps CRM fixture statuses to StatusBadge widgets. */
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

    public static function leadStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'new' => StatusBadge::make('New')->status('info'),
            'contacted' => StatusBadge::make('Contacted')->status('warning'),
            'qualified' => StatusBadge::make('Qualified')->status('success'),
            'unqualified' => StatusBadge::make('Unqualified')->status('neutral'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function companyStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'customer' => StatusBadge::make('Customer')->status('success'),
            'prospect' => StatusBadge::make('Prospect')->status('info'),
            'churned' => StatusBadge::make('Churned')->status('danger'),
            default => StatusBadge::make(ucfirst($status))->status('neutral'),
        };
    }

    public static function stageBadge(string $stage): StatusBadge
    {
        return match ($stage) {
            'Qualify' => StatusBadge::make($stage)->status('neutral'),
            'Discovery' => StatusBadge::make($stage)->status('info'),
            'Proposal' => StatusBadge::make($stage)->status('warning'),
            'Negotiation' => StatusBadge::make($stage)->status('warning'),
            'Closed Won' => StatusBadge::make($stage)->status('success'),
            'Closed Lost' => StatusBadge::make($stage)->status('danger'),
            default => StatusBadge::make($stage)->status('neutral'),
        };
    }

    public static function taskPriorityBadge(string $priority): StatusBadge
    {
        return match ($priority) {
            'high' => StatusBadge::make('High')->status('danger'),
            'medium' => StatusBadge::make('Medium')->status('warning'),
            'low' => StatusBadge::make('Low')->status('neutral'),
            default => StatusBadge::make(ucfirst($priority))->status('neutral'),
        };
    }

    public static function taskStatusBadge(string $status): StatusBadge
    {
        return match ($status) {
            'open' => StatusBadge::make('Open')->status('info'),
            'done' => StatusBadge::make('Done')->status('success'),
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
}
