<?php

declare(strict_types=1);

namespace Serixa\DevTools;

use Serixa\Contracts\ThemeInterface;
use Serixa\DevTools\Cli\CliRegistrar;
use Serixa\DevTools\Collector\AccessibilityCollector;
use Serixa\DevTools\Collector\AssetLoadingCollector;
use Serixa\DevTools\Collector\CliCommandsCollector;
use Serixa\DevTools\Collector\CollectorRegistry;
use Serixa\DevTools\Collector\EventTimelineCollector;
use Serixa\DevTools\Collector\MemoryUsageCollector;
use Serixa\DevTools\Collector\ProgressiveEnhancementCollector;
use Serixa\DevTools\Collector\RenderTimeCollector;
use Serixa\DevTools\Collector\ThemeResolutionCollector;
use Serixa\DevTools\Collector\WidgetCountCollector;
use Serixa\DevTools\Contracts\CollectorInterface;
use Serixa\DevTools\Contracts\InspectorInterface;
use Serixa\DevTools\Contracts\PanelInterface;
use Serixa\DevTools\Inspector\AssetTreeInspector;
use Serixa\DevTools\Inspector\ComponentTreeInspector;
use Serixa\DevTools\Inspector\DocumentTreeInspector;
use Serixa\DevTools\Inspector\PluginTreeInspector;
use Serixa\DevTools\Inspector\RenderTreeInspector;
use Serixa\DevTools\Inspector\ThemeTreeInspector;
use Serixa\DevTools\Inspector\WidgetTreeInspector;
use Serixa\DevTools\Panel\AccessibilityPanel;
use Serixa\DevTools\Panel\AssetsPanel;
use Serixa\DevTools\Panel\ConsolePanel;
use Serixa\DevTools\Panel\EventsPanel;
use Serixa\DevTools\Panel\OverviewPanel;
use Serixa\DevTools\Panel\PanelRegistry;
use Serixa\DevTools\Panel\PerformancePanel;
use Serixa\DevTools\Panel\PluginsPanel;
use Serixa\DevTools\Panel\RuntimePanel;
use Serixa\DevTools\Panel\ThemesPanel;
use Serixa\DevTools\Panel\WidgetsPanel;
use Serixa\DevTools\Runtime\DevToolsRuntime;
use Serixa\DevTools\Runtime\ElementInspector;
use Serixa\DevTools\Runtime\Injector;
use Serixa\DevTools\Runtime\Overlay;
use Serixa\DevTools\Support\CollectorContext;
use Serixa\Document\Document;

/**
 * Facade for SERIXA DevTools — near-zero overhead when disabled.
 */
final class DevTools
{
    private static bool $enabled = false;

    private static ?CollectorRegistry $collectors = null;

    private static ?PanelRegistry $panels = null;

    private static bool $defaultsBooted = false;

    private static bool $overlay = true;

    private static ?Document $attachedDocument = null;

    private static ?ThemeInterface $themeSnapshot = null;

    /** @var array<string, InspectorInterface>|null */
    private static ?array $inspectors = null;

    /** @var array<string, mixed>|null */
    private static ?array $lastCollection = null;

    /**
     * @return array<string, mixed>|null
     */
    public static function lastCollection(): ?array
    {
        return self::$lastCollection;
    }

    public static function enable(): void
    {
        self::$enabled = true;
        self::captureThemeSnapshot();
    }

    public static function disable(): void
    {
        self::$enabled = false;
    }

    public static function enabled(): bool
    {
        return self::$enabled;
    }

    /**
     * Theme observed when DevTools was enabled/attached (survives ThemeResolutionPass).
     */
    public static function themeSnapshot(): ?ThemeInterface
    {
        return self::$themeSnapshot;
    }

    public static function reset(): void
    {
        self::$enabled = false;
        self::$collectors = new CollectorRegistry();
        self::$panels = new PanelRegistry();
        self::$defaultsBooted = false;
        self::$overlay = true;
        self::$inspectors = null;
        self::$lastCollection = null;
        self::$attachedDocument = null;
        self::$themeSnapshot = null;
    }

    /**
     * @return array<string, mixed>
     */
    public static function collect(?CollectorContext $ctx = null): array
    {
        if (! self::$enabled) {
            return [];
        }

        self::ensureBooted();
        $ctx ??= CollectorContext::make(startedAt: hrtime(true));

        $data = self::collectors()->collectAll($ctx);
        $data['inspectors'] = self::runInspectors($ctx);
        $data['elements'] = (new ElementInspector())->buildPayload($ctx->widget());
        $data['package'] = [
            'name' => Package::NAME,
            'version' => Package::VERSION,
            'enabled' => true,
        ];

        self::$lastCollection = $data;

        return $data;
    }

    public static function panel(string $name): ?PanelInterface
    {
        if (! self::$enabled) {
            return null;
        }

        self::ensureBooted();

        return self::panels()->get($name);
    }

    public static function overlay(bool $show = true): void
    {
        self::$overlay = $show;
    }

    public static function registerCollector(CollectorInterface $collector): void
    {
        self::collectors()->register($collector);
    }

    public static function registerPanel(PanelInterface $panel): void
    {
        self::panels()->register($panel);
    }

    public static function bootDefaults(): void
    {
        if (self::$defaultsBooted) {
            return;
        }

        self::collectors()->register(new RenderTimeCollector());
        self::collectors()->register(new MemoryUsageCollector());
        self::collectors()->register(new WidgetCountCollector());
        self::collectors()->register(new ThemeResolutionCollector());
        self::collectors()->register(new AssetLoadingCollector());
        self::collectors()->register(new EventTimelineCollector());
        self::collectors()->register(new ProgressiveEnhancementCollector());
        self::collectors()->register(new AccessibilityCollector());
        self::collectors()->register(new CliCommandsCollector());

        self::panels()->register(new OverviewPanel());
        self::panels()->register(new WidgetsPanel());
        self::panels()->register(new ThemesPanel());
        self::panels()->register(new PerformancePanel());
        self::panels()->register(new AccessibilityPanel());
        self::panels()->register(new AssetsPanel());
        self::panels()->register(new EventsPanel());
        self::panels()->register(new RuntimePanel());
        self::panels()->register(new ConsolePanel());
        self::panels()->register(new PluginsPanel());

        self::$inspectors = [
            (new WidgetTreeInspector())->name() => new WidgetTreeInspector(),
            (new RenderTreeInspector())->name() => new RenderTreeInspector(),
            (new ComponentTreeInspector())->name() => new ComponentTreeInspector(),
            (new ThemeTreeInspector())->name() => new ThemeTreeInspector(),
            (new DocumentTreeInspector())->name() => new DocumentTreeInspector(),
            (new AssetTreeInspector())->name() => new AssetTreeInspector(),
            (new PluginTreeInspector())->name() => new PluginTreeInspector(),
        ];

        self::$defaultsBooted = true;
    }

    public static function attach(Document $document): Document
    {
        if (! self::$enabled) {
            return $document;
        }

        self::ensureBooted();
        self::captureThemeSnapshot();
        DevToolsRuntime::register($document->assets());
        self::$attachedDocument = $document;

        return $document;
    }

    public static function injectHtml(string $html, ?CollectorContext $ctx = null): string
    {
        if (! self::$enabled) {
            return $html;
        }

        self::ensureBooted();

        if ($ctx === null) {
            $document = self::$attachedDocument;
            $widget = null;

            if ($document !== null) {
                $children = $document->bodyChildren();
                $first = $children[0] ?? null;
                $widget = $first instanceof \Serixa\Contracts\WidgetInterface ? $first : null;
            }

            $ctx = CollectorContext::make(
                $widget,
                $document,
                $html,
                hrtime(true),
            );
        } elseif ($ctx->html() === '') {
            $ctx = CollectorContext::make(
                $ctx->widget(),
                $ctx->document(),
                $html,
                $ctx->startedAt() ?? hrtime(true),
                $ctx->extras(),
            );
        }

        $data = self::collect($ctx);

        if (! self::$overlay) {
            return $html;
        }

        $overlayHtml = (new Overlay())->render(self::panels()->all(), $data);

        return (new Injector())->injectHtml($html, $overlayHtml);
    }

    public static function profile(callable $callback): mixed
    {
        if (! self::$enabled) {
            return $callback();
        }

        $startedAt = hrtime(true);
        $result = $callback();
        self::collect(CollectorContext::make(startedAt: $startedAt));

        return $result;
    }

    public static function registerCli(): void
    {
        CliRegistrar::register();
    }

    private static function captureThemeSnapshot(): void
    {
        self::$themeSnapshot = \Serixa\Theme\ThemeManager::current();
    }

    private static function ensureBooted(): void
    {
        if (self::$collectors === null) {
            self::$collectors = new CollectorRegistry();
        }

        if (self::$panels === null) {
            self::$panels = new PanelRegistry();
        }

        self::bootDefaults();
    }

    private static function collectors(): CollectorRegistry
    {
        return self::$collectors ??= new CollectorRegistry();
    }

    private static function panels(): PanelRegistry
    {
        return self::$panels ??= new PanelRegistry();
    }

    /**
     * @return array<string, mixed>
     */
    private static function runInspectors(CollectorContext $ctx): array
    {
        self::ensureBooted();

        $results = [];
        $inspectors = self::$inspectors ?? [];

        foreach ($inspectors as $name => $inspector) {
            $subject = match ($name) {
                'document_tree', 'asset_tree' => $ctx->document(),
                'theme_tree' => self::$themeSnapshot,
                'plugin_tree' => $ctx->extras()['pluginManager'] ?? null,
                default => $ctx->widget(),
            };

            $results[$name] = $inspector->inspect($subject);
        }

        return $results;
    }
}
