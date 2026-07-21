<?php

declare(strict_types=1);

namespace NovaPHP\Tests\Document;

use NovaPHP\Component\Button;
use NovaPHP\Component\Center;
use NovaPHP\Component\Column;
use NovaPHP\Component\Text;
use NovaPHP\Document\Asset\AssetBundle;
use NovaPHP\Document\Asset\AssetManager;
use NovaPHP\Document\Asset\AssetManifest;
use NovaPHP\Document\Asset\AssetResolver;
use NovaPHP\Document\Document;
use NovaPHP\Document\DocumentRenderer;
use NovaPHP\Document\Element\Link;
use NovaPHP\Document\Element\Meta;
use NovaPHP\Document\Element\Script;
use NovaPHP\Document\Element\Style;
use NovaPHP\Document\Element\Title;
use NovaPHP\Document\Head;
use NovaPHP\Document\Seo\SeoManager;
use NovaPHP\Rendering\Renderer;
use NovaPHP\Theme\ThemeManager;
use PHPUnit\Framework\TestCase;

final class DocumentEngineTest extends TestCase
{
    protected function tearDown(): void
    {
        ThemeManager::reset();
        parent::tearDown();
    }

    public function testDocumentFluentApiProducesFullHtml(): void
    {
        $document = Document::make()
            ->title('Dashboard')
            ->description('SERIXA Admin')
            ->keywords(['dashboard', 'admin'])
            ->themeColor('#2563eb')
            ->language('en')
            ->canonical('https://example.com/dashboard')
            ->body(
                Center::make(
                    Column::make([
                        Text::make('Hello'),
                        Button::make('Continue')->primary(),
                    ]),
                ),
            );

        $html = (new Renderer())->render($document);

        $this->assertStringStartsWith('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('<html lang="en"', $html);
        $this->assertStringContainsString('<title>Dashboard</title>', $html);
        $this->assertStringContainsString('name="description"', $html);
        $this->assertStringContainsString('SERIXA Admin', $html);
        $this->assertStringContainsString('dashboard, admin', $html);
        $this->assertStringContainsString('theme-color', $html);
        $this->assertStringContainsString('#2563eb', $html);
        $this->assertStringContainsString('rel="canonical"', $html);
        $this->assertStringContainsString('Hello', $html);
        $this->assertStringContainsString('Continue', $html);
        $this->assertStringContainsString('</body></html>', $html);
    }

    public function testHeadElementsRender(): void
    {
        $head = Head::make()
            ->title('Page')
            ->meta(Meta::charset())
            ->meta(Meta::viewport())
            ->link(Link::stylesheet('/app.css'))
            ->style(Style::make('body{margin:0}'))
            ->script(Script::src('/app.js', defer: true));

        $html = $head->render();

        $this->assertStringContainsString('<title>Page</title>', $html);
        $this->assertStringContainsString('charset="UTF-8"', $html);
        $this->assertStringContainsString('viewport', $html);
        $this->assertStringContainsString('stylesheet', $html);
        $this->assertStringContainsString('body{margin:0}', $html);
        $this->assertStringContainsString('defer', $html);
    }

    public function testSeoManagerOpenGraphTwitterAndJsonLd(): void
    {
        $seo = new SeoManager();
        $seo->title('T')
            ->description('D')
            ->openGraph('og:type', 'website')
            ->twitter('twitter:site', '@serixa')
            ->jsonLd('{"@type":"WebSite"}')
            ->favicon('/favicon.ico')
            ->author('SERIXA')
            ->robots('noindex');

        $head = Head::make();
        $seo->applyTo($head);
        $html = $head->render();

        $this->assertStringContainsString('og:title', $html);
        $this->assertStringContainsString('og:type', $html);
        $this->assertStringContainsString('twitter:card', $html);
        $this->assertStringContainsString('twitter:site', $html);
        $this->assertStringContainsString('application/ld+json', $html);
        $this->assertStringContainsString('rel="icon"', $html);
        $this->assertStringContainsString('noindex', $html);
        $this->assertStringContainsString('author', $html);
    }

    public function testAssetManagerAndResolver(): void
    {
        $manifest = new AssetManifest(['app.css' => 'build/app.abc.css'], '2');
        $resolver = new AssetResolver($manifest, '/assets', '9');
        $manager = new AssetManager($resolver);

        $bundle = (new AssetBundle('app', '1.0.0'))
            ->css('app.css')
            ->js('app.js', ['defer' => true]);

        $manager->bundle($bundle)
            ->preload('hero.webp', 'image')
            ->font('Inter.woff2');

        $this->assertSame('/assets/build/app.abc.css?v=9', $resolver->resolve('app.css'));

        $head = Head::make();
        $manager->applyTo($head);
        $html = $head->render();

        $this->assertStringContainsString('build/app.abc.css', $html);
        $this->assertStringContainsString('app.js', $html);
        $this->assertStringContainsString('rel="preload"', $html);
        $this->assertStringContainsString('Inter.woff2', $html);
    }

    public function testTitleEscapesContent(): void
    {
        $html = Title::make('<script>x</script>')->render();

        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<script>x</script>', $html);
    }

    public function testDocumentRendererStandalone(): void
    {
        $html = DocumentRenderer::make()->render(
            Document::make()->title('Solo')->body(Text::make('Body')),
        );

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('Body', $html);
    }

    public function testFragmentRenderingStillWorks(): void
    {
        $html = (new Renderer())->render(Button::make('OK')->primary());

        $this->assertStringStartsWith('<button', $html);
        $this->assertStringNotContainsString('<!DOCTYPE html>', $html);
        $this->assertStringNotContainsString('<html', $html);
    }

    public function testMixedRenderDocumentAndWidget(): void
    {
        $renderer = new Renderer();
        $doc = Document::make()->title('A')->body(Text::make('Doc'));
        $fragment = Text::make('Frag');

        $html = $renderer->render($doc) . $renderer->render($fragment);

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('Doc', $html);
        $this->assertStringContainsString('Frag', $html);
    }
}
