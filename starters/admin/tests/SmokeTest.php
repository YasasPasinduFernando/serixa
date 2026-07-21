<?php

declare(strict_types=1);

namespace App\Tests;

use App\Web;
use PHPUnit\Framework\TestCase;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\ThemeManager;

/**
 * Renders every page in the starter and asserts on the shared shell plus a
 * page-specific marker, guarding against fatal errors and gross regressions.
 */
final class SmokeTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        $this->renderer = new Renderer();
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function pages(): iterable
    {
        yield 'dashboard' => ['dashboard', 'Dashboard'];
        yield 'users' => ['users', 'Users'];
        yield 'roles' => ['roles', 'Roles'];
        yield 'permissions' => ['permissions', 'Permissions'];
        yield 'departments' => ['departments', 'Departments'];
        yield 'products' => ['products', 'Products'];
        yield 'orders' => ['orders', 'Orders'];
        yield 'customers' => ['customers', 'Customers'];
        yield 'invoices' => ['invoices', 'Invoices'];
        yield 'reports' => ['reports', 'Reports'];
        yield 'analytics' => ['analytics', 'Analytics'];
        yield 'notifications' => ['notifications', 'Notifications'];
        yield 'audit-logs' => ['audit-logs', 'Audit Logs'];
        yield 'settings' => ['settings', 'Settings'];
        yield 'profile' => ['profile', 'Profile'];
    }

    /**
     * @dataProvider pages
     */
    public function testKnownPageRendersShellAndTitle(string $page, string $expectedText): void
    {
        $this->assertTrue(Web::isKnown($page), sprintf('"%s" should be a known route.', $page));

        $html = $this->renderer->render(Web::document($page));

        $this->assertStringContainsString('<html', $html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('SERIXA Admin', $html);
        $this->assertStringContainsString($expectedText, $html);
        $this->assertStringContainsString('<nav', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function testUnknownPageIsNotKnown(): void
    {
        $this->assertFalse(Web::isKnown('does-not-exist'));
    }

    public function testUnknownPageFallsBackToNotFoundDocument(): void
    {
        $html = $this->renderer->render(Web::document('does-not-exist'));

        $this->assertStringContainsString('<html', $html);
        $this->assertStringContainsString('Page not found', $html);
        $this->assertStringContainsString('<svg', $html);
    }

    public function testDashboardListsMetricsAndActivity(): void
    {
        $html = $this->renderer->render(Web::document('dashboard'));

        $this->assertStringContainsString('Active users', $html);
        $this->assertStringContainsString('Revenue summary', $html);
        $this->assertStringContainsString('Recent users', $html);
        $this->assertStringContainsString('Recent orders', $html);
        $this->assertStringContainsString('Activity feed', $html);
    }

    public function testUsersPageRendersTableChrome(): void
    {
        $html = $this->renderer->render(Web::document('users'));

        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('Ada Lovelace', $html);
        $this->assertStringContainsString('ada@serixa.dev', $html);
    }

    public function testOrdersPageRendersStatusBadges(): void
    {
        $html = $this->renderer->render(Web::document('orders'));

        $this->assertStringContainsString('ORD-1042', $html);
        $this->assertStringContainsString('Paid', $html);
        $this->assertStringContainsString('Processing', $html);
    }

    public function testReportsPageShowsChartPlaceholders(): void
    {
        $html = $this->renderer->render(Web::document('reports'));

        $this->assertStringContainsString('Revenue chart placeholder', $html);
        $this->assertStringContainsString('Monthly revenue', $html);
    }

    public function testProfilePageRendersAvatarAndSettingsSections(): void
    {
        $html = $this->renderer->render(Web::document('profile'));

        $this->assertStringContainsString('AL', $html);
        $this->assertStringContainsString('Security settings', $html);
        $this->assertStringContainsString('Notification settings', $html);
    }
}
