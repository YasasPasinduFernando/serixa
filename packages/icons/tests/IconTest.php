<?php

declare(strict_types=1);

namespace Serixa\Icons\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Icons\Component\Icon;
use Serixa\Icons\Exceptions\UnknownIconException;
use Serixa\Icons\Exceptions\UnknownPackException;
use Serixa\Icons\IconPack\Feather;
use Serixa\Icons\IconPack\Heroicons;
use Serixa\Icons\IconPack\Lucide;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Icons\Support\OutlineCatalog;
use Serixa\Rendering\Renderer;
use Serixa\Theme\ThemeManager;

final class IconTest extends TestCase
{
    protected function setUp(): void
    {
        IconRegistry::reset();
        ThemeManager::reset();
    }

    public function testMakeRendersInlineSvg(): void
    {
        $html = (new Renderer())->render(Icon::make('home'));

        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('xmlns="http://www.w3.org/2000/svg"', $html);
        $this->assertStringContainsString('viewBox="0 0 24 24"', $html);
        $this->assertStringContainsString('<path', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function testDecorativeDefaultsToAriaHidden(): void
    {
        $html = (new Renderer())->render(Icon::make('search'));

        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString('focusable="false"', $html);
        $this->assertStringNotContainsString('role="img"', $html);
    }

    public function testAriaLabelMakesInformativeIcon(): void
    {
        $html = (new Renderer())->render(Icon::make('mail')->ariaLabel('Email'));

        $this->assertStringContainsString('role="img"', $html);
        $this->assertStringContainsString('aria-label="Email"', $html);
        $this->assertStringNotContainsString('aria-hidden="true"', $html);
    }

    public function testTitleElementForInformativeIcon(): void
    {
        $html = (new Renderer())->render(Icon::make('bell')->title('Notifications'));

        $this->assertStringContainsString('<title>Notifications</title>', $html);
        $this->assertStringContainsString('role="img"', $html);
    }

    public function testDecorativeExplicit(): void
    {
        $html = (new Renderer())->render(Icon::make('home')->decorative());

        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    public function testSizeAndColorUseThemeClasses(): void
    {
        $html = (new Renderer())->render(Icon::make('star')->size('lg')->color('primary'));

        $this->assertStringContainsString('w-6', $html);
        $this->assertStringContainsString('h-6', $html);
        $this->assertStringContainsString('text-sky-600', $html);
    }

    public function testFilledVsOutlined(): void
    {
        $outline = (new Renderer())->render(Icon::make('heart')->outlined());
        $filled = (new Renderer())->render(Icon::make('heart')->filled());

        $this->assertStringContainsString('stroke="currentColor"', $outline);
        $this->assertStringContainsString('fill="currentColor"', $filled);
        $this->assertStringNotContainsString('stroke="currentColor"', $filled);
    }

    public function testStrokeWidth(): void
    {
        $html = (new Renderer())->render(Icon::make('plus')->strokeWidth(2.5));

        $this->assertStringContainsString('stroke-width="2.5"', $html);
    }

    public function testPackSwitching(): void
    {
        $html = (new Renderer())->render(Icon::make('search')->pack('feather'));
        $this->assertStringContainsString('<svg', $html);

        $html2 = (new Renderer())->render(Icon::make('lucide:house'));
        $this->assertStringContainsString('<svg', $html2);
    }

    public function testRegistryDefaultPackAndHas(): void
    {
        $registry = IconRegistry::default();

        $this->assertSame('heroicons', $registry->defaultPackName());
        $this->assertTrue($registry->has('home'));
        $this->assertTrue($registry->has('search', 'feather'));
        $this->assertFalse($registry->has('definitely-missing-icon-xyz'));
    }

    public function testRegistryResolveUnknownIcon(): void
    {
        $this->expectException(UnknownIconException::class);
        IconRegistry::default()->resolve('not-a-real-icon');
    }

    public function testRegistryUnknownPack(): void
    {
        $this->expectException(UnknownPackException::class);
        IconRegistry::default()->pack('nope');
    }

    public function testPackInterfaces(): void
    {
        $hero = new Heroicons();
        $feather = new Feather();
        $lucide = new Lucide();

        $this->assertSame('heroicons', $hero->name());
        $this->assertSame('feather', $feather->name());
        $this->assertSame('lucide', $lucide->name());
        $this->assertNotEmpty($hero->icons());
        $this->assertTrue($feather->has('search'));
        $this->assertTrue($lucide->has('house'));
    }

    public function testEveryCatalogIconRendersValidSvg(): void
    {
        $renderer = new Renderer();
        $count = 0;

        foreach (OutlineCatalog::names() as $name) {
            $html = $renderer->render(Icon::make($name));
            $this->assertStringStartsWith('<svg', $html, $name);
            $this->assertStringContainsString('</svg>', $html, $name);
            $this->assertStringContainsString('<path', $html, $name);
            $count++;
        }

        $this->assertGreaterThanOrEqual(100, $count);
    }

    public function testHeroiconsAliases(): void
    {
        $html = (new Renderer())->render(Icon::make('menu'));
        $this->assertStringContainsString('<svg', $html);
    }

    public function testClassFluentStillWorks(): void
    {
        $html = (new Renderer())->render(Icon::make('cog')->class('ml-2'));

        $this->assertStringContainsString('ml-2', $html);
    }
}
