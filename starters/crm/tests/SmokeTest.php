<?php

declare(strict_types=1);

namespace App\Tests;

use App\Web;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\Admin\Theme\AdminTheme;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class SmokeTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        ThemeManager::set(new AdminTheme(new DefaultTheme()));
        $this->renderer = new Renderer();
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function pages(): iterable
    {
        yield 'dashboard' => ['dashboard', 'Dashboard'];
        yield 'leads' => ['leads', 'Leads'];
        yield 'lead' => ['lead', 'Lead'];
        yield 'companies' => ['companies', 'Companies'];
        yield 'contacts' => ['contacts', 'Contacts'];
        yield 'opportunities' => ['opportunities', 'Opportunities'];
        yield 'pipeline' => ['pipeline', 'Pipeline'];
        yield 'tasks' => ['tasks', 'Tasks'];
        yield 'calendar' => ['calendar', 'Calendar'];
        yield 'meetings' => ['meetings', 'Meetings'];
        yield 'invoices' => ['invoices', 'Invoices'];
        yield 'reports' => ['reports', 'Reports'];
        yield 'analytics' => ['analytics', 'Analytics'];
        yield 'users' => ['users', 'Users'];
        yield 'roles' => ['roles', 'Roles'];
        yield 'settings' => ['settings', 'Settings'];
        yield 'profile' => ['profile', 'Profile'];
        yield 'notifications' => ['notifications', 'Notifications'];
    }

    #[DataProvider('pages')]
    public function testKnownPageRendersShellAndTitle(string $page, string $expectedText): void
    {
        $this->assertTrue(Web::isKnown($page));

        $query = $page === 'lead' ? ['id' => '1'] : [];
        $html = $this->renderer->render(Web::document($page, $query));

        $this->assertStringContainsString('<html', $html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('SERIXA CRM', $html);
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
        $this->assertStringContainsString('Page not found', $html);
        $this->assertStringContainsString('<html', $html);
    }

    public function testDashboardIncludesChartsAndMetrics(): void
    {
        $html = $this->renderer->render(Web::document('dashboard'));
        $this->assertStringContainsString('data-serixa-chart', $html);
        $this->assertStringContainsString('$428,400', $html);
        $this->assertStringContainsString('New lead', $html);
        $this->assertStringContainsString('Recent activity', $html);
    }

    public function testCalendarRendersGrid(): void
    {
        $html = $this->renderer->render(Web::document('calendar'));
        $this->assertStringContainsString('data-serixa-calendar', $html);
        $this->assertStringContainsString('role="grid"', $html);
    }

    public function testLeadDetailsWithId(): void
    {
        $html = $this->renderer->render(Web::document('lead', ['id' => '1']));
        $this->assertStringContainsString('Nora Quinn', $html);
        $this->assertStringContainsString('Northwind Labs', $html);
    }

    public function testKnownPagesCount(): void
    {
        $this->assertCount(18, Web::knownPages());
    }
}
