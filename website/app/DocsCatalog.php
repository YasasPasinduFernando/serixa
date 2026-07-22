<?php

declare(strict_types=1);

namespace App;

/**
 * Catalog of documentation pages and their markdown sources under /docs.
 *
 * @phpstan-type DocEntry array{slug: string, title: string, group: string, source: string, marker: string}
 */
final class DocsCatalog
{
    private function __construct()
    {
    }

    /**
     * @return list<DocEntry>
     */
    public static function all(): array
    {
        return [
            ['slug' => 'introduction', 'title' => 'Introduction', 'group' => 'Getting started', 'source' => 'getting-started.md', 'marker' => 'What SERIXA is'],
            ['slug' => 'installation', 'title' => 'Installation', 'group' => 'Getting started', 'source' => 'installation.md', 'marker' => 'Installing via Composer'],
            ['slug' => 'quick-start', 'title' => 'Quick Start', 'group' => 'Getting started', 'source' => 'getting-started.md', 'marker' => 'The fastest path'],
            ['slug' => 'first-application', 'title' => 'First Application', 'group' => 'Getting started', 'source' => 'first-blog.md', 'marker' => 'Build a blog'],
            ['slug' => 'rendering', 'title' => 'Rendering', 'group' => 'Core', 'source' => 'rendering-engine.md', 'marker' => 'Rendering'],
            ['slug' => 'widgets', 'title' => 'Widgets', 'group' => 'Core', 'source' => 'ui-foundation.md', 'marker' => 'UI Foundation'],
            ['slug' => 'layouts', 'title' => 'Layouts', 'group' => 'Core', 'source' => 'layout-engine.md', 'marker' => 'Layout engine'],
            ['slug' => 'forms', 'title' => 'Forms', 'group' => 'Core', 'source' => 'forms.md', 'marker' => 'Forms'],
            ['slug' => 'themes', 'title' => 'Themes', 'group' => 'Core', 'source' => 'layout-engine.md', 'marker' => 'Theme seam'],
            ['slug' => 'cli', 'title' => 'CLI', 'group' => 'Tooling', 'source' => 'cli.md', 'marker' => 'CLI'],
            ['slug' => 'packages', 'title' => 'Packages', 'group' => 'Ecosystem', 'source' => 'monorepo.md', 'marker' => 'Monorepo'],
            ['slug' => 'icons', 'title' => 'Icons', 'group' => 'Ecosystem', 'source' => 'icons.md', 'marker' => 'Icons'],
            ['slug' => 'admin', 'title' => 'Admin', 'group' => 'Ecosystem', 'source' => 'admin.md', 'marker' => 'Admin'],
            ['slug' => 'charts', 'title' => 'Charts', 'group' => 'Ecosystem', 'source' => 'charts.md', 'marker' => 'Charts'],
            ['slug' => 'calendar', 'title' => 'Calendar', 'group' => 'Ecosystem', 'source' => 'calendar.md', 'marker' => 'Calendar'],
            ['slug' => 'devtools', 'title' => 'DevTools', 'group' => 'Ecosystem', 'source' => 'devtools.md', 'marker' => 'DevTools'],
            ['slug' => 'starter-kits', 'title' => 'Starter Kits', 'group' => 'Ecosystem', 'source' => 'starter-blog.md', 'marker' => 'Starter'],
            ['slug' => 'deployment', 'title' => 'Deployment', 'group' => 'Guides', 'source' => 'deployment.md', 'marker' => 'Deployment'],
            ['slug' => 'faq', 'title' => 'FAQ', 'group' => 'Guides', 'source' => 'faq.md', 'marker' => 'FAQ'],
            ['slug' => 'roadmap', 'title' => 'Roadmap', 'group' => 'Guides', 'source' => '@roadmap', 'marker' => 'Roadmap'],
        ];
    }

    /**
     * @return DocEntry|null
     */
    public static function find(string $slug): ?array
    {
        foreach (self::all() as $entry) {
            if ($entry['slug'] === $slug) {
                return $entry;
            }
        }

        return null;
    }

    public static function route(string $slug): string
    {
        return 'docs-' . $slug;
    }

    public static function href(string $slug): string
    {
        return '?page=' . self::route($slug);
    }

    /**
     * @return list<DocEntry>
     */
    public static function group(string $group): array
    {
        return array_values(array_filter(
            self::all(),
            static fn (array $e): bool => $e['group'] === $group,
        ));
    }

    /**
     * @return list<string>
     */
    public static function groups(): array
    {
        $groups = [];

        foreach (self::all() as $entry) {
            if (! in_array($entry['group'], $groups, true)) {
                $groups[] = $entry['group'];
            }
        }

        return $groups;
    }

    /**
     * @return array{previous: ?DocEntry, next: ?DocEntry}
     */
    public static function neighbors(string $slug): array
    {
        $all = self::all();
        $index = null;

        foreach ($all as $i => $entry) {
            if ($entry['slug'] === $slug) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $index > 0 ? $all[$index - 1] : null,
            'next' => $index < count($all) - 1 ? $all[$index + 1] : null,
        ];
    }

    public static function resolvePath(string $source): string
    {
        /** @var array{docs_path: string, roadmap_path: string} $config */
        $config = require dirname(__DIR__) . '/config/app.php';

        if ($source === '@roadmap') {
            return $config['roadmap_path'];
        }

        return rtrim($config['docs_path'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $source;
    }
}
