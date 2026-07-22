<?php

declare(strict_types=1);

namespace Serixa\DevTools\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Cli\CommandRegistry;
use Serixa\Cli\InputOutput;
use Serixa\Component\Text;
use Serixa\DevTools\Cli\CliRegistrar;
use Serixa\DevTools\DevTools;
use Serixa\DevTools\Runtime\DevToolsRuntime;
use Serixa\DevTools\Support\CollectorContext;
use Serixa\DevTools\Theme\DevToolsTheme;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class DevToolsTest extends TestCase
{
    protected function setUp(): void
    {
        ThemeManager::reset();
        ThemeManager::set(new DevToolsTheme(new DefaultTheme()));
        DevTools::reset();
        CommandRegistry::resetDefault();
    }

    protected function tearDown(): void
    {
        DevTools::reset();
        ThemeManager::reset();
        CommandRegistry::resetDefault();
    }

    public function testDisabledCollectAndInjectAreNoOps(): void
    {
        DevTools::disable();
        $html = '<html><body><p>hi</p></body></html>';

        $this->assertSame([], DevTools::collect());
        $this->assertSame($html, DevTools::injectHtml($html));
        $this->assertNull(DevTools::panel('overview'));
    }

    public function testEnabledCollectAndOverlayInjection(): void
    {
        DevTools::enable();
        $widget = Text::make('Hello');
        $document = Document::make()->title('T')->body($widget);
        DevTools::attach($document);

        $html = (new Renderer())->render($document);
        $out = DevTools::injectHtml($html, CollectorContext::make($widget, $document, $html, hrtime(true)));

        $this->assertStringContainsString('id="serixa-devtools"', $out);
        $this->assertStringContainsString('data-serixa-devtools="1"', $out);
        $this->assertStringContainsString('aria-label="SERIXA DevTools"', $out);
        $this->assertStringContainsString('serixa-devtools-data', $out);
        $this->assertStringContainsString('Hello', $out);

        $data = DevTools::lastCollection();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('memory_usage', $data);
        $this->assertArrayHasKey('render_time', $data);
        $this->assertArrayHasKey('package', $data);
        $this->assertSame('0.1.0', $data['package']['version']);
    }

    public function testThemeResolvesKeys(): void
    {
        $theme = new DevToolsTheme(new DefaultTheme());

        foreach (['devtools.root', 'devtools.toolbar', 'devtools.panel', 'devtools.overlay'] as $key) {
            $this->assertNotEmpty($theme->classes($key), $key);
        }

        $this->assertNotEmpty($theme->classes('button'));
    }

    public function testRuntimeAssetsExist(): void
    {
        $this->assertFileExists(DevToolsRuntime::scriptPath());
        $this->assertFileExists(DevToolsRuntime::cssPath());
        $js = (string) file_get_contents(DevToolsRuntime::scriptPath());
        $this->assertStringNotContainsString('<?php', $js);
        $this->assertStringContainsString("Serixa.module('devtools'", $js);
        $this->assertStringContainsString('serixa-devtools-highlight', $js);
        $this->assertStringContainsString('aria-live', $js);
    }

    public function testProfileWhenDisabledJustRunsCallback(): void
    {
        DevTools::disable();
        $result = DevTools::profile(static fn (): int => 42);
        $this->assertSame(42, $result);
    }

    public function testOverviewPanelIsFirst(): void
    {
        DevTools::enable();
        $html = DevTools::injectHtml('<html><body></body></html>');
        $posOverview = strpos($html, 'data-devtools-tab="overview"');
        $posAccess = strpos($html, 'data-devtools-tab="accessibility"');
        $this->assertNotFalse($posOverview);
        $this->assertNotFalse($posAccess);
        $this->assertLessThan($posAccess, $posOverview);
    }

    public function testCliCommandsRegisterAndRun(): void
    {
        CliRegistrar::register();
        $registry = CommandRegistry::default();

        foreach (['inspect', 'profile', 'doctor', 'benchmark'] as $name) {
            $this->assertTrue($registry->has($name), $name);
        }

        $io = new InputOutput(true);
        $this->assertSame(0, $registry->get('inspect')->handle([], [], $io));
        $this->assertStringContainsString('PHP', $io->output());

        $doctorIo = new InputOutput(true);
        $this->assertSame(0, $registry->get('doctor')->handle([], [], $doctorIo));

        $benchIo = new InputOutput(true);
        $this->assertSame(0, $registry->get('benchmark')->handle([], ['times' => '5'], $benchIo));
        $this->assertStringContainsString('average', strtolower($benchIo->output()));

        DevTools::registerCli();
        $this->assertTrue(CommandRegistry::default()->has('inspect'));
    }
}
