<?php

declare(strict_types=1);

namespace App\Tests;

use App\DocsCatalog;
use App\SiteContext;
use App\Web;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\ThemeManager;

/**
 * Smoke-renders every public route.
 */
final class SmokeTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        SiteContext::boot('home', 'light');
        $this->renderer = new Renderer();
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function pages(): iterable
    {
        yield 'home' => ['home', 'Build expressive interfaces. Keep the web simple.'];
        yield 'packages' => ['packages', 'Official Packages'];
        yield 'playground' => ['playground', 'Playground'];
        yield 'examples-blog' => ['examples-blog', 'Blog Showcase'];
        yield 'examples-admin' => ['examples-admin', 'Admin Showcase'];
        yield 'examples-crm' => ['examples-crm', 'CRM Showcase'];
        yield 'examples-ecommerce' => ['examples-ecommerce', 'Ecommerce Showcase'];
        yield 'examples-portfolio' => ['examples-portfolio', 'Portfolio Showcase'];

        foreach (DocsCatalog::all() as $entry) {
            yield 'docs-' . $entry['slug'] => [
                DocsCatalog::route($entry['slug']),
                $entry['title'],
            ];
        }
    }

    #[DataProvider('pages')]
    public function testRouteRenders(string $page, string $expectedText): void
    {
        SiteContext::boot($page, 'light');
        $this->assertTrue(Web::isKnown($page), sprintf('"%s" should be known.', $page));

        $html = $this->renderer->render(Web::document($page));

        $this->assertStringContainsString('<html', $html);
        $this->assertStringContainsString('SERIXA', $html);
        $this->assertStringContainsString($expectedText, $html);
        $this->assertStringContainsString('site-nav', $html);
        $this->assertStringContainsString('/assets/serixa.css', $html);
        $this->assertStringNotContainsString('src="https://cdn.tailwindcss.com"', $html);
        $this->assertStringNotContainsString('/assets/vendor/tailwindcss.js', $html);
    }

    public function testAllRoutesHaveSmokeCoverage(): void
    {
        $expected = Web::allRoutes();
        $covered = [];

        foreach (self::pages() as $row) {
            $covered[] = $row[0];
        }

        sort($expected);
        sort($covered);

        $this->assertSame($expected, $covered);
    }

    public function testUnknownPageFallsBack(): void
    {
        SiteContext::boot('missing', 'light');
        $this->assertFalse(Web::isKnown('missing'));

        $html = $this->renderer->render(Web::document('missing'));

        $this->assertStringContainsString('Page not found', $html);
    }

    public function testDarkThemeAttribute(): void
    {
        SiteContext::boot('home', 'dark');
        $html = $this->renderer->render(Web::document('home'));

        $this->assertStringContainsString('data-theme="dark"', $html);
    }

    public function testHomepageShowcasesRealFrameworkComponents(): void
    {
        $html = $this->renderer->render(Web::document('home'));

        $this->assertStringContainsString('Built with SERIXA', $html);
        $this->assertStringContainsString('data-serixa-alert', $html);
        $this->assertStringContainsString('data-serixa-progress', $html);
        $this->assertStringContainsString('Explore every component', $html);
    }
}
