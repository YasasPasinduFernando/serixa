<?php

declare(strict_types=1);

namespace App;

use Serixa\Document\Document;

/**
 * Tiny page map: resolves a `?page=` query value to the matching page class.
 * No router package, no middleware — a plain lookup is enough for a static
 * UI starter.
 */
final class Web
{
    /** @var array<string, class-string> */
    private const ROUTES = [
        'dashboard' => DashboardPage::class,
        'users' => UsersPage::class,
        'roles' => RolesPage::class,
        'permissions' => PermissionsPage::class,
        'departments' => DepartmentsPage::class,
        'products' => ProductsPage::class,
        'orders' => OrdersPage::class,
        'customers' => CustomersPage::class,
        'invoices' => InvoicesPage::class,
        'reports' => ReportsPage::class,
        'analytics' => AnalyticsPage::class,
        'notifications' => NotificationsPage::class,
        'audit-logs' => AuditLogsPage::class,
        'settings' => SettingsPage::class,
        'profile' => ProfilePage::class,
    ];

    private function __construct()
    {
    }

    public static function isKnown(string $page): bool
    {
        return isset(self::ROUTES[$page]);
    }

    /**
     * @return list<string>
     */
    public static function knownPages(): array
    {
        return array_keys(self::ROUTES);
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(string $page, array $query = []): Document
    {
        $class = self::ROUTES[$page] ?? null;

        if ($class === null) {
            return NotFoundPage::document($query);
        }

        return $class::document($query);
    }
}
