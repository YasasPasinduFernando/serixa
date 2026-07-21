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
     * @return iterable<string, array{string, string, array<string, mixed>}>
     */
    public static function pages(): iterable
    {
        yield 'dashboard' => ['dashboard', 'Dashboard', []];
        yield 'posts' => ['posts', 'Posts', []];
        yield 'create-post' => ['create-post', 'New post', []];
        yield 'edit-post' => ['edit-post', 'Edit post', ['id' => '2']];
        yield 'categories' => ['categories', 'Categories', []];
        yield 'tags' => ['tags', 'Tags', []];
        yield 'media' => ['media', 'Media', []];
        yield 'comments' => ['comments', 'Comments', []];
        yield 'users' => ['users', 'Users', []];
        yield 'profile' => ['profile', 'Profile', []];
        yield 'settings' => ['settings', 'Settings', []];
    }

    /**
     * @dataProvider pages
     * @param array<string, mixed> $query
     */
    public function testKnownPageRendersShellAndTitle(string $page, string $expectedText, array $query): void
    {
        $this->assertTrue(Web::isKnown($page), sprintf('"%s" should be a known route.', $page));

        $html = $this->renderer->render(Web::document($page, $query));

        $this->assertStringContainsString('<html', $html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('SERIXA Blog', $html);
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

    public function testEditPostFallsBackToFirstPostWhenIdIsMissing(): void
    {
        $html = $this->renderer->render(Web::document('edit-post'));

        $this->assertStringContainsString('Edit post', $html);
        $this->assertStringContainsString('value="', $html);
    }

    public function testDashboardListsMetrics(): void
    {
        $html = $this->renderer->render(Web::document('dashboard'));

        $this->assertStringContainsString('Published posts', $html);
        $this->assertStringContainsString('Recent posts', $html);
    }

    public function testPostsPageRendersDataTableAndActions(): void
    {
        $html = $this->renderer->render(Web::document('posts'));

        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('Announcing SERIXA', $html);
    }

    public function testUsersPageRendersUserChips(): void
    {
        $html = $this->renderer->render(Web::document('users'));

        $this->assertStringContainsString('Ada Lovelace', $html);
        $this->assertStringContainsString('ada@serixa.dev', $html);
    }

    public function testCommentsPageRendersStatusBadges(): void
    {
        $html = $this->renderer->render(Web::document('comments'));

        $this->assertStringContainsString('Pending', $html);
        $this->assertStringContainsString('Approved', $html);
    }
}
