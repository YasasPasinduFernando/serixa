<?php

declare(strict_types=1);

namespace App;

use Serixa\Document\Document;

/**
 * Resolves `?page=` to page document builders.
 */
final class Web
{
    /** @var array<string, class-string> */
    private const ROUTES = [
        'home' => HomePage::class,
        'packages' => PackagesPage::class,
        'playground' => PlaygroundPage::class,
        'examples-blog' => Examples\BlogExamplePage::class,
        'examples-admin' => Examples\AdminExamplePage::class,
        'examples-crm' => Examples\CrmExamplePage::class,
        'examples-ecommerce' => Examples\EcommerceExamplePage::class,
        'examples-portfolio' => Examples\PortfolioExamplePage::class,
    ];

    private function __construct()
    {
    }

    public static function isKnown(string $page): bool
    {
        if (isset(self::ROUTES[$page])) {
            return true;
        }

        if (str_starts_with($page, 'docs-')) {
            $slug = substr($page, 5);

            return DocsCatalog::find($slug) !== null;
        }

        return false;
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(string $page, array $query = []): Document
    {
        if (str_starts_with($page, 'docs-')) {
            $slug = substr($page, 5);

            return DocPage::document($slug, $query);
        }

        $class = self::ROUTES[$page] ?? null;

        if ($class === null) {
            return NotFoundPage::document($query);
        }

        return $class::document($query);
    }

    /**
     * @return list<string>
     */
    public static function allRoutes(): array
    {
        $routes = array_keys(self::ROUTES);

        foreach (DocsCatalog::all() as $entry) {
            $routes[] = DocsCatalog::route($entry['slug']);
        }

        return $routes;
    }
}
