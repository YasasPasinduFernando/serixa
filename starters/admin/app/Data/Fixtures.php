<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Static, in-memory fake data for the SERIXA Admin Starter.
 *
 * There is no database, ORM, or persistence layer here — every method
 * returns a plain PHP array so the starter can be rendered without any
 * infrastructure. Swap this class out for a real repository/service layer
 * when you build on top of this starter.
 */
final class Fixtures
{
    /**
     * @return array{
     *     id: int, name: string, email: string, role: string, department: string,
     *     title: string, initials: string, joined: string, timezone: string
     * }
     */
    public static function currentUser(): array
    {
        return [
            'id' => 1,
            'name' => 'Ada Lovelace',
            'email' => 'ada@serixa.dev',
            'role' => 'Owner',
            'department' => 'Engineering',
            'title' => 'Head of Platform',
            'initials' => 'AL',
            'joined' => '2025-11-02',
            'timezone' => 'UTC',
        ];
    }

    /**
     * @return list<array{
     *     id: int, name: string, email: string, role: string, department: string,
     *     status: string, lastActive: string, joined: string
     * }>
     */
    public static function users(): array
    {
        return [
            ['id' => 1, 'name' => 'Ada Lovelace', 'email' => 'ada@serixa.dev', 'role' => 'Owner', 'department' => 'Engineering', 'status' => 'active', 'lastActive' => 'Just now', 'joined' => '2025-11-02'],
            ['id' => 2, 'name' => 'Grace Hopper', 'email' => 'grace@serixa.dev', 'role' => 'Admin', 'department' => 'Engineering', 'status' => 'active', 'lastActive' => '12 min ago', 'joined' => '2025-11-08'],
            ['id' => 3, 'name' => 'Alan Turing', 'email' => 'alan@serixa.dev', 'role' => 'Manager', 'department' => 'Operations', 'status' => 'active', 'lastActive' => '1 hour ago', 'joined' => '2025-12-01'],
            ['id' => 4, 'name' => 'Margaret Hamilton', 'email' => 'margaret@serixa.dev', 'role' => 'Editor', 'department' => 'Marketing', 'status' => 'invited', 'lastActive' => '—', 'joined' => '2026-07-10'],
            ['id' => 5, 'name' => 'Katherine Johnson', 'email' => 'katherine@serixa.dev', 'role' => 'Analyst', 'department' => 'Finance', 'status' => 'active', 'lastActive' => 'Yesterday', 'joined' => '2026-01-15'],
            ['id' => 6, 'name' => 'Tim Berners-Lee', 'email' => 'tim@serixa.dev', 'role' => 'Support', 'department' => 'Support', 'status' => 'suspended', 'lastActive' => '3 weeks ago', 'joined' => '2026-02-20'],
            ['id' => 7, 'name' => 'Barbara Liskov', 'email' => 'barbara@serixa.dev', 'role' => 'Admin', 'department' => 'Engineering', 'status' => 'active', 'lastActive' => '4 hours ago', 'joined' => '2026-03-04'],
            ['id' => 8, 'name' => 'Donald Knuth', 'email' => 'donald@serixa.dev', 'role' => 'Viewer', 'department' => 'Research', 'status' => 'active', 'lastActive' => '2 days ago', 'joined' => '2026-04-12'],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function user(int $id): ?array
    {
        foreach (self::users() as $user) {
            if ($user['id'] === $id) {
                return $user;
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
            ['id' => 1, 'name' => 'Owner', 'slug' => 'owner', 'users' => 1, 'description' => 'Full control of the workspace, billing, and membership.'],
            ['id' => 2, 'name' => 'Admin', 'slug' => 'admin', 'users' => 2, 'description' => 'Manage users, roles, and most operational settings.'],
            ['id' => 3, 'name' => 'Manager', 'slug' => 'manager', 'users' => 1, 'description' => 'Oversee departments, orders, and team workflows.'],
            ['id' => 4, 'name' => 'Editor', 'slug' => 'editor', 'users' => 1, 'description' => 'Create and update catalog content and campaigns.'],
            ['id' => 5, 'name' => 'Analyst', 'slug' => 'analyst', 'users' => 1, 'description' => 'Read reports and export analytics datasets.'],
            ['id' => 6, 'name' => 'Support', 'slug' => 'support', 'users' => 1, 'description' => 'Handle customer tickets and order exceptions.'],
            ['id' => 7, 'name' => 'Viewer', 'slug' => 'viewer', 'users' => 1, 'description' => 'Read-only access to dashboards and shared reports.'],
        ];
    }

    /**
     * @return list<array{id: int, name: string, group: string, description: string}>
     */
    public static function permissions(): array
    {
        return [
            ['id' => 1, 'name' => 'users.view', 'group' => 'People', 'description' => 'View the user directory.'],
            ['id' => 2, 'name' => 'users.manage', 'group' => 'People', 'description' => 'Invite, update, and deactivate users.'],
            ['id' => 3, 'name' => 'roles.manage', 'group' => 'People', 'description' => 'Create and assign roles.'],
            ['id' => 4, 'name' => 'orders.view', 'group' => 'Commerce', 'description' => 'View order history.'],
            ['id' => 5, 'name' => 'orders.refund', 'group' => 'Commerce', 'description' => 'Issue refunds and cancellations.'],
            ['id' => 6, 'name' => 'products.manage', 'group' => 'Commerce', 'description' => 'Create and update products.'],
            ['id' => 7, 'name' => 'invoices.manage', 'group' => 'Finance', 'description' => 'Create and send invoices.'],
            ['id' => 8, 'name' => 'reports.export', 'group' => 'Insights', 'description' => 'Export generated reports.'],
            ['id' => 9, 'name' => 'audit.view', 'group' => 'Security', 'description' => 'Read the audit log.'],
            ['id' => 10, 'name' => 'settings.manage', 'group' => 'System', 'description' => 'Change workspace settings.'],
        ];
    }

    /**
     * @return list<array{id: int, name: string, code: string, lead: string, members: int, location: string}>
     */
    public static function departments(): array
    {
        return [
            ['id' => 1, 'name' => 'Engineering', 'code' => 'ENG', 'lead' => 'Ada Lovelace', 'members' => 12, 'location' => 'Remote'],
            ['id' => 2, 'name' => 'Operations', 'code' => 'OPS', 'lead' => 'Alan Turing', 'members' => 8, 'location' => 'London'],
            ['id' => 3, 'name' => 'Marketing', 'code' => 'MKT', 'lead' => 'Margaret Hamilton', 'members' => 6, 'location' => 'New York'],
            ['id' => 4, 'name' => 'Finance', 'code' => 'FIN', 'lead' => 'Katherine Johnson', 'members' => 5, 'location' => 'Remote'],
            ['id' => 5, 'name' => 'Support', 'code' => 'SUP', 'lead' => 'Tim Berners-Lee', 'members' => 10, 'location' => 'Colombo'],
            ['id' => 6, 'name' => 'Research', 'code' => 'RSH', 'lead' => 'Donald Knuth', 'members' => 4, 'location' => 'Remote'],
        ];
    }

    /**
     * @return list<array{
     *     id: int, sku: string, name: string, category: string, price: string,
     *     stock: int, status: string, updatedAt: string
     * }>
     */
    public static function products(): array
    {
        return [
            ['id' => 1, 'sku' => 'SX-1001', 'name' => 'SERIXA Cloud Seat', 'category' => 'Subscriptions', 'price' => '$49.00', 'stock' => 999, 'status' => 'active', 'updatedAt' => '2026-07-18'],
            ['id' => 2, 'sku' => 'SX-1002', 'name' => 'Admin Add-on', 'category' => 'Add-ons', 'price' => '$19.00', 'stock' => 999, 'status' => 'active', 'updatedAt' => '2026-07-16'],
            ['id' => 3, 'sku' => 'SX-2001', 'name' => 'Icon Pack Pro', 'category' => 'Assets', 'price' => '$29.00', 'stock' => 240, 'status' => 'active', 'updatedAt' => '2026-07-12'],
            ['id' => 4, 'sku' => 'SX-3001', 'name' => 'Hardware Kit', 'category' => 'Hardware', 'price' => '$189.00', 'stock' => 18, 'status' => 'active', 'updatedAt' => '2026-07-08'],
            ['id' => 5, 'sku' => 'SX-4001', 'name' => 'Onboarding Bundle', 'category' => 'Services', 'price' => '$990.00', 'stock' => 40, 'status' => 'draft', 'updatedAt' => '2026-07-20'],
            ['id' => 6, 'sku' => 'SX-5001', 'name' => 'Legacy Starter', 'category' => 'Subscriptions', 'price' => '$9.00', 'stock' => 0, 'status' => 'archived', 'updatedAt' => '2026-05-01'],
        ];
    }

    /**
     * @return list<array{
     *     id: int, number: string, customer: string, total: string, items: int,
     *     status: string, placedAt: string
     * }>
     */
    public static function orders(): array
    {
        return [
            ['id' => 1042, 'number' => 'ORD-1042', 'customer' => 'Northwind Labs', 'total' => '$1,284.00', 'items' => 4, 'status' => 'paid', 'placedAt' => '2026-07-20'],
            ['id' => 1041, 'number' => 'ORD-1041', 'customer' => 'Contoso Retail', 'total' => '$482.50', 'items' => 2, 'status' => 'processing', 'placedAt' => '2026-07-19'],
            ['id' => 1040, 'number' => 'ORD-1040', 'customer' => 'Fabrikam Co', 'total' => '$2,910.00', 'items' => 8, 'status' => 'fulfilled', 'placedAt' => '2026-07-18'],
            ['id' => 1039, 'number' => 'ORD-1039', 'customer' => 'Adventure Works', 'total' => '$129.00', 'items' => 1, 'status' => 'pending', 'placedAt' => '2026-07-17'],
            ['id' => 1038, 'number' => 'ORD-1038', 'customer' => 'Tailspin Toys', 'total' => '$76.00', 'items' => 3, 'status' => 'cancelled', 'placedAt' => '2026-07-16'],
            ['id' => 1037, 'number' => 'ORD-1037', 'customer' => 'Wide World Importers', 'total' => '$3,450.00', 'items' => 12, 'status' => 'paid', 'placedAt' => '2026-07-15'],
        ];
    }

    /**
     * @return list<array{
     *     id: int, name: string, email: string, company: string, orders: int,
     *     lifetime: string, status: string, since: string
     * }>
     */
    public static function customers(): array
    {
        return [
            ['id' => 1, 'name' => 'Priya Shah', 'email' => 'priya@northwind.io', 'company' => 'Northwind Labs', 'orders' => 18, 'lifetime' => '$24,810', 'status' => 'active', 'since' => '2025-09-12'],
            ['id' => 2, 'name' => 'Marcus Chen', 'email' => 'marcus@contoso.com', 'company' => 'Contoso Retail', 'orders' => 9, 'lifetime' => '$8,420', 'status' => 'active', 'since' => '2025-11-03'],
            ['id' => 3, 'name' => 'Elena Rossi', 'email' => 'elena@fabrikam.co', 'company' => 'Fabrikam Co', 'orders' => 22, 'lifetime' => '$41,200', 'status' => 'active', 'since' => '2025-06-21'],
            ['id' => 4, 'name' => 'Jonah Blake', 'email' => 'jonah@adventure.works', 'company' => 'Adventure Works', 'orders' => 3, 'lifetime' => '$1,120', 'status' => 'invited', 'since' => '2026-07-01'],
            ['id' => 5, 'name' => 'Sofia Alvarez', 'email' => 'sofia@tailspin.toys', 'company' => 'Tailspin Toys', 'orders' => 11, 'lifetime' => '$6,780', 'status' => 'suspended', 'since' => '2025-12-18'],
            ['id' => 6, 'name' => 'Noah Patel', 'email' => 'noah@wideworld.com', 'company' => 'Wide World Importers', 'orders' => 31, 'lifetime' => '$58,940', 'status' => 'active', 'since' => '2025-04-02'],
        ];
    }

    /**
     * @return list<array{
     *     id: int, number: string, customer: string, amount: string,
     *     status: string, issuedAt: string, dueAt: string
     * }>
     */
    public static function invoices(): array
    {
        return [
            ['id' => 1, 'number' => 'INV-2201', 'customer' => 'Northwind Labs', 'amount' => '$1,284.00', 'status' => 'paid', 'issuedAt' => '2026-07-01', 'dueAt' => '2026-07-15'],
            ['id' => 2, 'number' => 'INV-2202', 'customer' => 'Contoso Retail', 'amount' => '$482.50', 'status' => 'open', 'issuedAt' => '2026-07-08', 'dueAt' => '2026-07-22'],
            ['id' => 3, 'number' => 'INV-2203', 'customer' => 'Fabrikam Co', 'amount' => '$2,910.00', 'status' => 'paid', 'issuedAt' => '2026-06-28', 'dueAt' => '2026-07-12'],
            ['id' => 4, 'number' => 'INV-2204', 'customer' => 'Adventure Works', 'amount' => '$129.00', 'status' => 'overdue', 'issuedAt' => '2026-06-15', 'dueAt' => '2026-06-30'],
            ['id' => 5, 'number' => 'INV-2205', 'customer' => 'Wide World Importers', 'amount' => '$3,450.00', 'status' => 'draft', 'issuedAt' => '2026-07-20', 'dueAt' => '2026-08-03'],
        ];
    }

    /**
     * @return list<array{id: int, name: string, type: string, generated: string, status: string}>
     */
    public static function reports(): array
    {
        return [
            ['id' => 1, 'name' => 'Monthly revenue', 'type' => 'Finance', 'generated' => 'Jul 1, 2026', 'status' => 'ready'],
            ['id' => 2, 'name' => 'User growth', 'type' => 'Growth', 'generated' => 'Jul 5, 2026', 'status' => 'ready'],
            ['id' => 3, 'name' => 'Order fulfillment', 'type' => 'Operations', 'generated' => 'Jul 12, 2026', 'status' => 'ready'],
            ['id' => 4, 'name' => 'Churn analysis', 'type' => 'Growth', 'generated' => 'Jul 18, 2026', 'status' => 'processing'],
            ['id' => 5, 'name' => 'Q2 compliance audit', 'type' => 'Compliance', 'generated' => '—', 'status' => 'failed'],
        ];
    }

    /**
     * @return list<array{id: int, title: string, channel: string, status: string, receivedAt: string}>
     */
    public static function notifications(): array
    {
        return [
            ['id' => 1, 'title' => 'Invoice INV-2204 is overdue', 'channel' => 'Email', 'status' => 'unread', 'receivedAt' => '2026-07-21 08:12'],
            ['id' => 2, 'title' => 'New user invited: Margaret Hamilton', 'channel' => 'In-app', 'status' => 'unread', 'receivedAt' => '2026-07-20 16:40'],
            ['id' => 3, 'title' => 'Weekly revenue report is ready', 'channel' => 'Email', 'status' => 'read', 'receivedAt' => '2026-07-20 09:00'],
            ['id' => 4, 'title' => 'Order ORD-1041 needs review', 'channel' => 'Slack', 'status' => 'read', 'receivedAt' => '2026-07-19 14:22'],
            ['id' => 5, 'title' => 'Security alert: new login from Colombo', 'channel' => 'In-app', 'status' => 'unread', 'receivedAt' => '2026-07-19 11:05'],
        ];
    }

    /**
     * @return list<array{
     *     id: int, actor: string, action: string, target: string,
     *     severity: string, at: string, ip: string
     * }>
     */
    public static function auditLogs(): array
    {
        return [
            ['id' => 1, 'actor' => 'Ada Lovelace', 'action' => 'role.updated', 'target' => 'Admin', 'severity' => 'info', 'at' => '2026-07-21 10:14', 'ip' => '203.0.113.10'],
            ['id' => 2, 'actor' => 'Grace Hopper', 'action' => 'user.invited', 'target' => 'margaret@serixa.dev', 'severity' => 'info', 'at' => '2026-07-20 16:40', 'ip' => '203.0.113.22'],
            ['id' => 3, 'actor' => 'System', 'action' => 'login.failed', 'target' => 'tim@serixa.dev', 'severity' => 'warning', 'at' => '2026-07-20 02:11', 'ip' => '198.51.100.44'],
            ['id' => 4, 'actor' => 'Katherine Johnson', 'action' => 'invoice.sent', 'target' => 'INV-2202', 'severity' => 'info', 'at' => '2026-07-19 15:02', 'ip' => '203.0.113.55'],
            ['id' => 5, 'actor' => 'System', 'action' => 'permission.denied', 'target' => 'settings.manage', 'severity' => 'critical', 'at' => '2026-07-18 21:33', 'ip' => '198.51.100.9'],
            ['id' => 6, 'actor' => 'Alan Turing', 'action' => 'order.refunded', 'target' => 'ORD-1038', 'severity' => 'warning', 'at' => '2026-07-16 12:48', 'ip' => '203.0.113.71'],
        ];
    }

    /**
     * @return list<array{id: int, actor: string, summary: string, at: string}>
     */
    public static function activityFeed(): array
    {
        return [
            ['id' => 1, 'actor' => 'Ada Lovelace', 'summary' => 'Updated workspace branding settings.', 'at' => '8 minutes ago'],
            ['id' => 2, 'actor' => 'Grace Hopper', 'summary' => 'Invited Margaret Hamilton as Editor.', 'at' => '2 hours ago'],
            ['id' => 3, 'actor' => 'System', 'summary' => 'Generated the Monthly revenue report.', 'at' => 'Yesterday'],
            ['id' => 4, 'actor' => 'Alan Turing', 'summary' => 'Marked ORD-1040 as fulfilled.', 'at' => 'Yesterday'],
            ['id' => 5, 'actor' => 'Katherine Johnson', 'summary' => 'Sent invoice INV-2202 to Contoso Retail.', 'at' => '2 days ago'],
        ];
    }

    /**
     * @return array{mrr: string, arr: string, avgOrder: string, refundRate: string, conversion: string, nps: string}
     */
    public static function revenueSummary(): array
    {
        return [
            'mrr' => '$48,120',
            'arr' => '$577,440',
            'avgOrder' => '$186',
            'refundRate' => '1.4%',
            'conversion' => '3.8%',
            'nps' => '62',
        ];
    }

    /**
     * @return array{sessions: string, bounce: string, activeAccounts: string, supportOpen: string}
     */
    public static function analyticsSnapshot(): array
    {
        return [
            'sessions' => '128,420',
            'bounce' => '41%',
            'activeAccounts' => '4,812',
            'supportOpen' => '37',
        ];
    }
}
