<?php

declare(strict_types=1);

namespace App;

/**
 * Official package catalog shown on the Packages page and homepage.
 *
 * @phpstan-type PackageEntry array{name: string, title: string, description: string, href: string}
 */
final class PackageCatalog
{
    private function __construct()
    {
    }

    /**
     * @return list<PackageEntry>
     */
    public static function all(): array
    {
        return [
            [
                'name' => 'serixa/framework',
                'title' => 'Framework',
                'description' => 'SSR widget engine — Document, Renderer, ThemeManager, and the core component library.',
                'href' => DocsCatalog::href('introduction'),
            ],
            [
                'name' => 'serixa/icons',
                'title' => 'Icons',
                'description' => 'Official icon set with theme-aware SVG widgets.',
                'href' => DocsCatalog::href('icons'),
            ],
            [
                'name' => 'serixa/forms',
                'title' => 'Forms',
                'description' => 'Form helpers and validation primitives for SERIXA UIs.',
                'href' => DocsCatalog::href('forms'),
            ],
            [
                'name' => 'serixa/admin',
                'title' => 'Admin',
                'description' => 'Admin shell, sidebar, top bar, and panel layouts for dashboards.',
                'href' => DocsCatalog::href('admin'),
            ],
            [
                'name' => 'serixa/charts',
                'title' => 'Charts',
                'description' => 'SSR SVG charts with progressive enhancement — UI only.',
                'href' => DocsCatalog::href('charts'),
            ],
            [
                'name' => 'serixa/calendar',
                'title' => 'Calendar',
                'description' => 'Month, week, day, and agenda calendar widgets.',
                'href' => DocsCatalog::href('calendar'),
            ],
            [
                'name' => 'serixa/cli',
                'title' => 'CLI',
                'description' => 'Scaffolding: serixa new, make:page, make:layout, build, and docs.',
                'href' => DocsCatalog::href('cli'),
            ],
            [
                'name' => 'serixa/devtools',
                'title' => 'DevTools',
                'description' => 'Development-only inspectors, collectors, and overlays.',
                'href' => DocsCatalog::href('devtools'),
            ],
            [
                'name' => 'serixa/auth-ui',
                'title' => 'Auth UI',
                'description' => 'Login, register, and profile UI surfaces — HTML only.',
                'href' => DocsCatalog::href('packages'),
            ],
        ];
    }
}
