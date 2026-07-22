<?php

declare(strict_types=1);

namespace App;

use Serixa\Document\Document;

/**
 * Tiny page map: resolves `?page=` to a page class.
 */
final class Web
{
    /** @var array<string, class-string> */
    private const ROUTES = [
        'dashboard' => DashboardPage::class,
        'leads' => LeadsPage::class,
        'lead' => LeadDetailsPage::class,
        'companies' => CompaniesPage::class,
        'contacts' => ContactsPage::class,
        'opportunities' => OpportunitiesPage::class,
        'pipeline' => PipelinePage::class,
        'tasks' => TasksPage::class,
        'calendar' => CalendarPage::class,
        'meetings' => MeetingsPage::class,
        'invoices' => InvoicesPage::class,
        'reports' => ReportsPage::class,
        'analytics' => AnalyticsPage::class,
        'users' => UsersPage::class,
        'roles' => RolesPage::class,
        'settings' => SettingsPage::class,
        'profile' => ProfilePage::class,
        'notifications' => NotificationsPage::class,
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
