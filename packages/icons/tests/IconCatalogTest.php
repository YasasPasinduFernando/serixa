<?php

declare(strict_types=1);

namespace Serixa\Icons\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\Icons\Component\Icon;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Icons\Support\OutlineCatalog;
use Serixa\Rendering\Renderer;
use Serixa\Theme\ThemeManager;

final class IconCatalogTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        IconRegistry::reset();
        ThemeManager::reset();
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        IconRegistry::reset();
        ThemeManager::reset();
        parent::tearDown();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function iconNameProvider(): array
    {
        $cases = [];
        foreach (OutlineCatalog::names() as $name) {
            $cases[$name] = [$name];
        }

        return $cases;
    }

    #[DataProvider('iconNameProvider')]
    public function testEveryCatalogIconRendersSvg(string $name): void
    {
        $html = $this->renderer->render(Icon::make($name));
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString('focusable="false"', $html);
        $this->assertStringContainsString('<path', $html);
        $this->assertStringContainsString('viewBox="0 0 24 24"', $html);
    }
}
