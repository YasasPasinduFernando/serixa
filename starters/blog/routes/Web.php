<?php

declare(strict_types=1);

namespace App;

use Serixa\Document\Document;

/**
 * Tiny page map: resolves a `?page=` query value to the matching page class.
 * No router package, no middleware — a plain `match()` is enough for a
 * static UI starter.
 */
final class Web
{
    /** @var array<string, class-string> */
    private const ROUTES = [
        'dashboard' => DashboardPage::class,
        'posts' => PostsPage::class,
        'create-post' => CreatePostPage::class,
        'edit-post' => EditPostPage::class,
        'categories' => CategoriesPage::class,
        'tags' => TagsPage::class,
        'media' => MediaPage::class,
        'comments' => CommentsPage::class,
        'users' => UsersPage::class,
        'profile' => ProfilePage::class,
        'settings' => SettingsPage::class,
    ];

    private function __construct()
    {
    }

    public static function isKnown(string $page): bool
    {
        return isset(self::ROUTES[$page]);
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(string $page, array $query = []): Document
    {
        $class = self::ROUTES[$page] ?? null;

        if ($class === null) {
            return NotFoundPage::document($query);
        }

        return $class::document($query);
    }
}
