<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Static fake CRM data. No database, ORM, auth, or sessions.
 */
final class Fixtures
{
    /**
     * @return array{id: int, name: string, email: string, role: string, title: string, initials: string, team: string}
     */
    public static function currentUser(): array
    {
        return [
            'id' => 1,
            'name' => 'Ada Lovelace',
            'email' => 'ada@serixa.dev',
            'role' => 'Owner',
            'title' => 'VP Sales',
            'initials' => 'AL',
            'team' => 'Enterprise',
        ];
    }

    /**
     * @return array{revenue: string, openDeals: int, activeLeads: int, conversion: string, pipelineValue: string, avgDeal: string}
     */
    public static function crmKpis(): array
    {
        return [
            'revenue' => '$428,400',
            'openDeals' => 17,
            'activeLeads' => 42,
            'conversion' => '18.4%',
            'pipelineValue' => '$1.24M',
            'avgDeal' => '$24,600',
        ];
    }

    /**
     * @return list<array{month: string, revenue: float, deals: int}>
     */
    public static function revenueByMonth(): array
    {
        return [
            ['month' => 'Jan', 'revenue' => 32000, 'deals' => 4],
            ['month' => 'Feb', 'revenue' => 41000, 'deals' => 5],
            ['month' => 'Mar', 'revenue' => 38500, 'deals' => 4],
            ['month' => 'Apr', 'revenue' => 52000, 'deals' => 7],
            ['month' => 'May', 'revenue' => 61000, 'deals' => 8],
            ['month' => 'Jun', 'revenue' => 57400, 'deals' => 6],
            ['month' => 'Jul', 'revenue' => 66500, 'deals' => 9],
        ];
    }

    /**
     * @return list<array{stage: string, count: int, value: float}>
     */
    public static function pipelineStages(): array
    {
        return [
            ['stage' => 'Qualify', 'count' => 8, 'value' => 186000],
            ['stage' => 'Discovery', 'count' => 6, 'value' => 244000],
            ['stage' => 'Proposal', 'count' => 5, 'value' => 312000],
            ['stage' => 'Negotiation', 'count' => 4, 'value' => 298000],
            ['stage' => 'Closed Won', 'count' => 12, 'value' => 428400],
        ];
    }

    /**
     * @return list<array{id: int, name: string, email: string, role: string, team: string, status: string, lastActive: string}>
     */
    public static function users(): array
    {
        return [
            ['id' => 1, 'name' => 'Ada Lovelace', 'email' => 'ada@serixa.dev', 'role' => 'Owner', 'team' => 'Enterprise', 'status' => 'active', 'lastActive' => 'Just now'],
            ['id' => 2, 'name' => 'Grace Hopper', 'email' => 'grace@serixa.dev', 'role' => 'Admin', 'team' => 'Enterprise', 'status' => 'active', 'lastActive' => '8 min ago'],
            ['id' => 3, 'name' => 'Alan Turing', 'email' => 'alan@serixa.dev', 'role' => 'Manager', 'team' => 'Mid-Market', 'status' => 'active', 'lastActive' => '1 hour ago'],
            ['id' => 4, 'name' => 'Katherine Johnson', 'email' => 'katherine@serixa.dev', 'role' => 'Rep', 'team' => 'SMB', 'status' => 'active', 'lastActive' => 'Yesterday'],
            ['id' => 5, 'name' => 'Margaret Hamilton', 'email' => 'margaret@serixa.dev', 'role' => 'Rep', 'team' => 'SMB', 'status' => 'invited', 'lastActive' => '—'],
        ];
    }

    /** @return array<string, mixed>|null */
    public static function user(int $id): ?array
    {
        foreach (self::users() as $row) {
            if ($row['id'] === $id) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return list<array{id: int, name: string, slug: string, users: int, description: string}>
     */
    public static function roles(): array
    {
        return [
            ['id' => 1, 'name' => 'Owner', 'slug' => 'owner', 'users' => 1, 'description' => 'Full CRM control including billing and roles.'],
            ['id' => 2, 'name' => 'Admin', 'slug' => 'admin', 'users' => 1, 'description' => 'Manage users, pipelines, and workspace settings.'],
            ['id' => 3, 'name' => 'Manager', 'slug' => 'manager', 'users' => 1, 'description' => 'Oversee team pipeline and forecasts.'],
            ['id' => 4, 'name' => 'Rep', 'slug' => 'rep', 'users' => 2, 'description' => 'Own leads, deals, tasks, and meetings.'],
            ['id' => 5, 'name' => 'Viewer', 'slug' => 'viewer', 'users' => 0, 'description' => 'Read-only dashboards and reports.'],
        ];
    }

    /**
     * @return list<array{id: int, name: string, industry: string, city: string, employees: int, owner: string, status: string}>
     */
    public static function companies(): array
    {
        return [
            ['id' => 1, 'name' => 'Northwind Labs', 'industry' => 'SaaS', 'city' => 'Austin', 'employees' => 120, 'owner' => 'Ada Lovelace', 'status' => 'customer'],
            ['id' => 2, 'name' => 'Contoso Health', 'industry' => 'Healthcare', 'city' => 'Boston', 'employees' => 850, 'owner' => 'Grace Hopper', 'status' => 'prospect'],
            ['id' => 3, 'name' => 'Fabrikam Retail', 'industry' => 'Retail', 'city' => 'Chicago', 'employees' => 2400, 'owner' => 'Alan Turing', 'status' => 'customer'],
            ['id' => 4, 'name' => 'Adventure Works', 'industry' => 'Manufacturing', 'city' => 'Denver', 'employees' => 410, 'owner' => 'Katherine Johnson', 'status' => 'prospect'],
            ['id' => 5, 'name' => 'Tailspin Tours', 'industry' => 'Travel', 'city' => 'Seattle', 'employees' => 95, 'owner' => 'Ada Lovelace', 'status' => 'churned'],
        ];
    }

    /**
     * @return list<array{id: int, name: string, email: string, title: string, company: string, phone: string, owner: string}>
     */
    public static function contacts(): array
    {
        return [
            ['id' => 1, 'name' => 'Nora Quinn', 'email' => 'nora@northwind.example', 'title' => 'CTO', 'company' => 'Northwind Labs', 'phone' => '+1 512-555-0142', 'owner' => 'Ada Lovelace'],
            ['id' => 2, 'name' => 'Sam Irons', 'email' => 'sam@contoso.example', 'title' => 'VP Ops', 'company' => 'Contoso Health', 'phone' => '+1 617-555-0199', 'owner' => 'Grace Hopper'],
            ['id' => 3, 'name' => 'Leah Park', 'email' => 'leah@fabrikam.example', 'title' => 'Buyer', 'company' => 'Fabrikam Retail', 'phone' => '+1 312-555-0177', 'owner' => 'Alan Turing'],
            ['id' => 4, 'name' => 'Omar Diaz', 'email' => 'omar@adventure.example', 'title' => 'Plant Manager', 'company' => 'Adventure Works', 'phone' => '+1 303-555-0110', 'owner' => 'Katherine Johnson'],
            ['id' => 5, 'name' => 'Priya Shah', 'email' => 'priya@tailspin.example', 'title' => 'Founder', 'company' => 'Tailspin Tours', 'phone' => '+1 206-555-0164', 'owner' => 'Ada Lovelace'],
        ];
    }

    /**
     * @return list<array{id: int, name: string, company: string, source: string, status: string, score: int, owner: string, value: string, updated: string}>
     */
    public static function leads(): array
    {
        return [
            ['id' => 1, 'name' => 'Nora Quinn', 'company' => 'Northwind Labs', 'source' => 'Inbound', 'status' => 'qualified', 'score' => 86, 'owner' => 'Ada Lovelace', 'value' => '$42,000', 'updated' => '2026-07-20'],
            ['id' => 2, 'name' => 'Sam Irons', 'company' => 'Contoso Health', 'source' => 'Partner', 'status' => 'new', 'score' => 62, 'owner' => 'Grace Hopper', 'value' => '$95,000', 'updated' => '2026-07-21'],
            ['id' => 3, 'name' => 'Leah Park', 'company' => 'Fabrikam Retail', 'source' => 'Outbound', 'status' => 'contacted', 'score' => 71, 'owner' => 'Alan Turing', 'value' => '$28,500', 'updated' => '2026-07-18'],
            ['id' => 4, 'name' => 'Omar Diaz', 'company' => 'Adventure Works', 'source' => 'Event', 'status' => 'qualified', 'score' => 79, 'owner' => 'Katherine Johnson', 'value' => '$61,000', 'updated' => '2026-07-19'],
            ['id' => 5, 'name' => 'Priya Shah', 'company' => 'Tailspin Tours', 'source' => 'Inbound', 'status' => 'unqualified', 'score' => 34, 'owner' => 'Ada Lovelace', 'value' => '$12,000', 'updated' => '2026-07-12'],
            ['id' => 6, 'name' => 'Chris Vale', 'company' => 'Litware Inc', 'source' => 'Outbound', 'status' => 'new', 'score' => 55, 'owner' => 'Margaret Hamilton', 'value' => '$18,000', 'updated' => '2026-07-22'],
        ];
    }

    /** @return array<string, mixed>|null */
    public static function lead(int $id): ?array
    {
        foreach (self::leads() as $row) {
            if ($row['id'] === $id) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return list<array{id: int, name: string, company: string, stage: string, amount: string, amountRaw: float, closeDate: string, owner: string, probability: int}>
     */
    public static function opportunities(): array
    {
        return [
            ['id' => 1, 'name' => 'Northwind Platform', 'company' => 'Northwind Labs', 'stage' => 'Negotiation', 'amount' => '$84,000', 'amountRaw' => 84000, 'closeDate' => '2026-08-15', 'owner' => 'Ada Lovelace', 'probability' => 70],
            ['id' => 2, 'name' => 'Contoso EMR Rollout', 'company' => 'Contoso Health', 'stage' => 'Proposal', 'amount' => '$190,000', 'amountRaw' => 190000, 'closeDate' => '2026-09-01', 'owner' => 'Grace Hopper', 'probability' => 55],
            ['id' => 3, 'name' => 'Fabrikam POS Suite', 'company' => 'Fabrikam Retail', 'stage' => 'Discovery', 'amount' => '$62,000', 'amountRaw' => 62000, 'closeDate' => '2026-08-30', 'owner' => 'Alan Turing', 'probability' => 35],
            ['id' => 4, 'name' => 'Adventure MES', 'company' => 'Adventure Works', 'stage' => 'Qualify', 'amount' => '$48,000', 'amountRaw' => 48000, 'closeDate' => '2026-10-12', 'owner' => 'Katherine Johnson', 'probability' => 20],
            ['id' => 5, 'name' => 'Tailspin Renew', 'company' => 'Tailspin Tours', 'stage' => 'Closed Won', 'amount' => '$36,400', 'amountRaw' => 36400, 'closeDate' => '2026-07-01', 'owner' => 'Ada Lovelace', 'probability' => 100],
            ['id' => 6, 'name' => 'Litware Pilot', 'company' => 'Litware Inc', 'stage' => 'Proposal', 'amount' => '$27,500', 'amountRaw' => 27500, 'closeDate' => '2026-08-08', 'owner' => 'Margaret Hamilton', 'probability' => 45],
        ];
    }

    /**
     * @return list<array{id: int, title: string, related: string, due: string, priority: string, status: string, owner: string}>
     */
    public static function tasks(): array
    {
        return [
            ['id' => 1, 'title' => 'Send security questionnaire', 'related' => 'Contoso EMR Rollout', 'due' => '2026-07-23', 'priority' => 'high', 'status' => 'open', 'owner' => 'Grace Hopper'],
            ['id' => 2, 'title' => 'Schedule product demo', 'related' => 'Northwind Platform', 'due' => '2026-07-24', 'priority' => 'medium', 'status' => 'open', 'owner' => 'Ada Lovelace'],
            ['id' => 3, 'title' => 'Collect pricing feedback', 'related' => 'Fabrikam POS Suite', 'due' => '2026-07-25', 'priority' => 'medium', 'status' => 'done', 'owner' => 'Alan Turing'],
            ['id' => 4, 'title' => 'Prepare ROI one-pager', 'related' => 'Litware Pilot', 'due' => '2026-07-26', 'priority' => 'low', 'status' => 'open', 'owner' => 'Margaret Hamilton'],
            ['id' => 5, 'title' => 'Confirm stakeholder list', 'related' => 'Adventure MES', 'due' => '2026-07-27', 'priority' => 'high', 'status' => 'open', 'owner' => 'Katherine Johnson'],
        ];
    }

    /**
     * @return list<array{id: int, title: string, company: string, start: string, end: string, location: string, owner: string}>
     */
    public static function meetings(): array
    {
        return [
            ['id' => 1, 'title' => 'Discovery call', 'company' => 'Contoso Health', 'start' => '2026-07-22 10:00', 'end' => '2026-07-22 10:45', 'location' => 'Zoom', 'owner' => 'Grace Hopper'],
            ['id' => 2, 'title' => 'Security review', 'company' => 'Northwind Labs', 'start' => '2026-07-23 14:00', 'end' => '2026-07-23 15:00', 'location' => 'Teams', 'owner' => 'Ada Lovelace'],
            ['id' => 3, 'title' => 'Pricing workshop', 'company' => 'Fabrikam Retail', 'start' => '2026-07-24 09:30', 'end' => '2026-07-24 11:00', 'location' => 'On-site', 'owner' => 'Alan Turing'],
            ['id' => 4, 'title' => 'Pilot kickoff', 'company' => 'Litware Inc', 'start' => '2026-07-25 16:00', 'end' => '2026-07-25 16:30', 'location' => 'Google Meet', 'owner' => 'Margaret Hamilton'],
        ];
    }

    /**
     * @return list<array{id: int, number: string, company: string, amount: string, status: string, due: string, issued: string}>
     */
    public static function invoices(): array
    {
        return [
            ['id' => 1, 'number' => 'INV-1042', 'company' => 'Northwind Labs', 'amount' => '$18,400', 'status' => 'paid', 'due' => '2026-06-30', 'issued' => '2026-06-01'],
            ['id' => 2, 'number' => 'INV-1048', 'company' => 'Fabrikam Retail', 'amount' => '$9,250', 'status' => 'open', 'due' => '2026-07-31', 'issued' => '2026-07-01'],
            ['id' => 3, 'number' => 'INV-1051', 'company' => 'Contoso Health', 'amount' => '$42,000', 'status' => 'overdue', 'due' => '2026-07-15', 'issued' => '2026-06-15'],
            ['id' => 4, 'number' => 'INV-1055', 'company' => 'Tailspin Tours', 'amount' => '$6,800', 'status' => 'draft', 'due' => '2026-08-15', 'issued' => '2026-07-20'],
        ];
    }

    /**
     * @return list<array{id: int, title: string, type: string, status: string, owner: string, updated: string}>
     */
    public static function reports(): array
    {
        return [
            ['id' => 1, 'title' => 'Weekly pipeline snapshot', 'type' => 'Pipeline', 'status' => 'ready', 'owner' => 'Ada Lovelace', 'updated' => '2026-07-21'],
            ['id' => 2, 'title' => 'Lead source mix', 'type' => 'Leads', 'status' => 'ready', 'owner' => 'Grace Hopper', 'updated' => '2026-07-20'],
            ['id' => 3, 'title' => 'Rep activity scorecard', 'type' => 'Activity', 'status' => 'processing', 'owner' => 'Alan Turing', 'updated' => '2026-07-22'],
            ['id' => 4, 'title' => 'Invoice aging', 'type' => 'Finance', 'status' => 'ready', 'owner' => 'Katherine Johnson', 'updated' => '2026-07-18'],
        ];
    }

    /**
     * @return list<array{id: int, title: string, body: string, status: string, at: string}>
     */
    public static function notifications(): array
    {
        return [
            ['id' => 1, 'title' => 'Deal moved to Negotiation', 'body' => 'Northwind Platform advanced by Ada Lovelace.', 'status' => 'unread', 'at' => '12 min ago'],
            ['id' => 2, 'title' => 'Meeting in 1 hour', 'body' => 'Discovery call with Contoso Health.', 'status' => 'unread', 'at' => '48 min ago'],
            ['id' => 3, 'title' => 'Invoice overdue', 'body' => 'INV-1051 for Contoso Health is past due.', 'status' => 'unread', 'at' => '2 hours ago'],
            ['id' => 4, 'title' => 'New lead assigned', 'body' => 'Chris Vale was assigned to Margaret Hamilton.', 'status' => 'read', 'at' => 'Yesterday'],
        ];
    }

    /**
     * @return list<array{actor: string, summary: string, at: string}>
     */
    public static function activityFeed(): array
    {
        return [
            ['actor' => 'Ada Lovelace', 'summary' => 'Updated Northwind Platform probability to 70%.', 'at' => '10 min ago'],
            ['actor' => 'Grace Hopper', 'summary' => 'Logged a discovery call with Contoso Health.', 'at' => '45 min ago'],
            ['actor' => 'Alan Turing', 'summary' => 'Created task: Collect pricing feedback.', 'at' => '2 hours ago'],
            ['actor' => 'Katherine Johnson', 'summary' => 'Qualified Adventure Works lead.', 'at' => 'Yesterday'],
            ['actor' => 'System', 'summary' => 'Generated weekly pipeline snapshot.', 'at' => 'Yesterday'],
        ];
    }

    /**
     * @return list<array{title: string, start: string, end: string, color: string}>
     */
    public static function calendarEvents(): array
    {
        $out = [];
        foreach (self::meetings() as $meeting) {
            $out[] = [
                'title' => $meeting['title'],
                'start' => $meeting['start'],
                'end' => $meeting['end'],
                'color' => '#0f766e',
            ];
        }

        return $out;
    }
}
