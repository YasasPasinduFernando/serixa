<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Static, in-memory fake data for the SERIXA Blog Starter.
 *
 * There is no database, ORM, or persistence layer here — every method
 * returns a plain PHP array so the starter can be rendered without any
 * infrastructure. Swap this class out for a real repository/service layer
 * when you build on top of this starter.
 */
final class Fixtures
{
    /**
     * @return list<array{
     *     id: int, title: string, slug: string, excerpt: string, body: string,
     *     status: string, categoryId: int, category: string, tags: list<string>,
     *     authorId: int, author: string, comments: int, views: int, featured: bool,
     *     publishedAt: string, updatedAt: string,
     * }>
     */
    public static function posts(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Announcing SERIXA: A Flutter-Inspired PHP UI Framework',
                'slug' => 'announcing-serixa',
                'excerpt' => 'Write PHP widgets, get semantic HTML. Here is why we built SERIXA and where it is headed.',
                'body' => "SERIXA lets you compose server-rendered UI from small, typed PHP widgets instead of hand-rolled templates. No client-side framework required — just fast, accessible HTML.\n\nIn this post we walk through the motivation, the widget tree, and how the theming layer keeps markup consistent across an entire product.",
                'status' => 'published',
                'categoryId' => 1,
                'category' => 'Announcements',
                'tags' => ['php', 'serixa', 'release'],
                'authorId' => 1,
                'author' => 'Ada Lovelace',
                'comments' => 12,
                'views' => 4820,
                'featured' => true,
                'publishedAt' => '2026-06-02',
                'updatedAt' => '2026-06-03',
            ],
            [
                'id' => 2,
                'title' => 'Building Accessible Forms Without JavaScript',
                'slug' => 'accessible-forms-without-javascript',
                'excerpt' => 'Progressive enhancement is not dead. Here is how SERIXA renders forms that work everywhere, by default.',
                'body' => 'Every SERIXA form control ships with the right ARIA attributes out of the box. This post covers labels, described-by text, and validation states that degrade gracefully without a single line of client-side script.',
                'status' => 'published',
                'categoryId' => 2,
                'category' => 'Engineering',
                'tags' => ['accessibility', 'forms', 'html'],
                'authorId' => 2,
                'author' => 'Grace Hopper',
                'comments' => 8,
                'views' => 3110,
                'featured' => true,
                'publishedAt' => '2026-06-10',
                'updatedAt' => '2026-06-10',
            ],
            [
                'id' => 3,
                'title' => 'Theming 101: Tokens, Themes, and Tailwind',
                'slug' => 'theming-101-tokens-themes-tailwind',
                'excerpt' => 'A tour of design tokens and how the theme layer maps widget state to Tailwind utility classes.',
                'body' => 'Design tokens keep spacing, color, and radius decisions in one place. We show how AdminTheme wraps DefaultTheme to add admin-specific presentation without touching the framework core.',
                'status' => 'published',
                'categoryId' => 2,
                'category' => 'Engineering',
                'tags' => ['design-systems', 'tailwind', 'theming'],
                'authorId' => 1,
                'author' => 'Ada Lovelace',
                'comments' => 5,
                'views' => 1980,
                'featured' => false,
                'publishedAt' => '2026-06-18',
                'updatedAt' => '2026-06-19',
            ],
            [
                'id' => 4,
                'title' => 'How We Ship Icons Without a Build Step',
                'slug' => 'icons-without-a-build-step',
                'excerpt' => 'serixa/icons renders inline SVG straight from PHP. No sprite sheets, no bundlers, no runtime fetches.',
                'body' => 'We compare inline SVG against icon fonts and sprite sheets, then walk through how IconRegistry resolves a name like "home" to a themed, accessible <svg> element at render time.',
                'status' => 'published',
                'categoryId' => 2,
                'category' => 'Engineering',
                'tags' => ['icons', 'svg', 'performance'],
                'authorId' => 3,
                'author' => 'Alan Turing',
                'comments' => 3,
                'views' => 1420,
                'featured' => false,
                'publishedAt' => '2026-06-25',
                'updatedAt' => '2026-06-25',
            ],
            [
                'id' => 5,
                'title' => 'A First Look at the Admin Widget Kit',
                'slug' => 'first-look-admin-widget-kit',
                'excerpt' => 'serixa/admin gives you dashboards, tables, and shells out of the box. Here is a tour of what shipped.',
                'body' => 'From MetricCard to DataTable to the full AppShell, serixa/admin composes on top of the core framework. This post walks through building a dashboard end to end.',
                'status' => 'draft',
                'categoryId' => 1,
                'category' => 'Announcements',
                'tags' => ['serixa', 'admin', 'release'],
                'authorId' => 2,
                'author' => 'Grace Hopper',
                'comments' => 0,
                'views' => 0,
                'featured' => false,
                'publishedAt' => '—',
                'updatedAt' => '2026-07-14',
            ],
            [
                'id' => 6,
                'title' => 'Server-Rendered Widgets vs. Client Frameworks',
                'slug' => 'server-rendered-widgets-vs-client-frameworks',
                'excerpt' => 'When does it make sense to skip the client bundle entirely? A practical comparison.',
                'body' => 'We look at time-to-first-byte, SEO, and maintenance cost across a small e-commerce demo built with SERIXA and an equivalent React SPA.',
                'status' => 'published',
                'categoryId' => 3,
                'category' => 'Opinion',
                'tags' => ['architecture', 'performance'],
                'authorId' => 4,
                'author' => 'Margaret Hamilton',
                'comments' => 21,
                'views' => 6304,
                'featured' => false,
                'publishedAt' => '2026-07-01',
                'updatedAt' => '2026-07-02',
            ],
            [
                'id' => 7,
                'title' => 'Writing Your First Custom Widget',
                'slug' => 'writing-your-first-custom-widget',
                'excerpt' => 'Extend the Component base class and plug straight into the render pipeline. A step-by-step guide.',
                'body' => 'This tutorial builds a small Callout widget from scratch: constructor, childrenForTree(), render(), and a theme entry — everything you need to ship a reusable widget.',
                'status' => 'draft',
                'categoryId' => 4,
                'category' => 'Tutorials',
                'tags' => ['tutorial', 'widgets'],
                'authorId' => 5,
                'author' => 'Katherine Johnson',
                'comments' => 0,
                'views' => 0,
                'featured' => false,
                'publishedAt' => '—',
                'updatedAt' => '2026-07-18',
            ],
            [
                'id' => 8,
                'title' => 'Scheduled: SERIXA v1.0 Roadmap',
                'slug' => 'serixa-v1-roadmap',
                'excerpt' => 'A look ahead at what is planned for the 1.0 release across the framework, icons, and admin packages.',
                'body' => 'From stabilizing the public API to expanding the starter kit lineup, here is what the SERIXA team is planning for the road to 1.0.',
                'status' => 'scheduled',
                'categoryId' => 1,
                'category' => 'Announcements',
                'tags' => ['serixa', 'roadmap'],
                'authorId' => 1,
                'author' => 'Ada Lovelace',
                'comments' => 0,
                'views' => 0,
                'featured' => true,
                'publishedAt' => '2026-08-01',
                'updatedAt' => '2026-07-20',
            ],
            [
                'id' => 9,
                'title' => 'Testing Server-Rendered Widgets with PHPUnit',
                'slug' => 'testing-server-rendered-widgets',
                'excerpt' => 'Snapshot-free testing strategies for HTML output: string assertions, structure checks, and more.',
                'body' => 'We cover a pragmatic approach to testing render output — asserting on landmarks, ARIA attributes, and key text rather than brittle full-HTML snapshots.',
                'status' => 'published',
                'categoryId' => 4,
                'category' => 'Tutorials',
                'tags' => ['testing', 'phpunit', 'tutorial'],
                'authorId' => 3,
                'author' => 'Alan Turing',
                'comments' => 6,
                'views' => 2210,
                'featured' => false,
                'publishedAt' => '2026-07-08',
                'updatedAt' => '2026-07-09',
            ],
        ];
    }

    /**
     * @return array{
     *     id: int, title: string, slug: string, excerpt: string, body: string,
     *     status: string, categoryId: int, category: string, tags: list<string>,
     *     authorId: int, author: string, comments: int, views: int, featured: bool,
     *     publishedAt: string, updatedAt: string,
     * }|null
     */
    public static function post(int $id): ?array
    {
        foreach (self::posts() as $post) {
            if ($post['id'] === $id) {
                return $post;
            }
        }

        return null;
    }

    /**
     * @return array{id: int, name: string, slug: string, description: string, postCount: int}|null
     */
    public static function category(int $id): ?array
    {
        foreach (self::categories() as $category) {
            if ($category['id'] === $id) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @return list<array{id: int, name: string, slug: string, description: string, postCount: int}>
     */
    public static function categories(): array
    {
        return [
            ['id' => 1, 'name' => 'Announcements', 'slug' => 'announcements', 'description' => 'Product news and release notes.', 'postCount' => 3],
            ['id' => 2, 'name' => 'Engineering', 'slug' => 'engineering', 'description' => 'Deep dives into how SERIXA is built.', 'postCount' => 3],
            ['id' => 3, 'name' => 'Opinion', 'slug' => 'opinion', 'description' => 'Perspectives on the wider web ecosystem.', 'postCount' => 1],
            ['id' => 4, 'name' => 'Tutorials', 'slug' => 'tutorials', 'description' => 'Step-by-step guides for building with SERIXA.', 'postCount' => 2],
        ];
    }

    /**
     * @return list<array{id: int, name: string, slug: string, postCount: int}>
     */
    public static function tags(): array
    {
        return [
            ['id' => 1, 'name' => 'php', 'slug' => 'php', 'postCount' => 1],
            ['id' => 2, 'name' => 'serixa', 'slug' => 'serixa', 'postCount' => 3],
            ['id' => 3, 'name' => 'release', 'slug' => 'release', 'postCount' => 2],
            ['id' => 4, 'name' => 'accessibility', 'slug' => 'accessibility', 'postCount' => 1],
            ['id' => 5, 'name' => 'forms', 'slug' => 'forms', 'postCount' => 1],
            ['id' => 6, 'name' => 'html', 'slug' => 'html', 'postCount' => 1],
            ['id' => 7, 'name' => 'design-systems', 'slug' => 'design-systems', 'postCount' => 1],
            ['id' => 8, 'name' => 'tailwind', 'slug' => 'tailwind', 'postCount' => 1],
            ['id' => 9, 'name' => 'theming', 'slug' => 'theming', 'postCount' => 1],
            ['id' => 10, 'name' => 'icons', 'slug' => 'icons', 'postCount' => 1],
            ['id' => 11, 'name' => 'svg', 'slug' => 'svg', 'postCount' => 1],
            ['id' => 12, 'name' => 'performance', 'slug' => 'performance', 'postCount' => 2],
            ['id' => 13, 'name' => 'admin', 'slug' => 'admin', 'postCount' => 1],
            ['id' => 14, 'name' => 'architecture', 'slug' => 'architecture', 'postCount' => 1],
            ['id' => 15, 'name' => 'tutorial', 'slug' => 'tutorial', 'postCount' => 2],
            ['id' => 16, 'name' => 'widgets', 'slug' => 'widgets', 'postCount' => 1],
            ['id' => 17, 'name' => 'roadmap', 'slug' => 'roadmap', 'postCount' => 1],
            ['id' => 18, 'name' => 'testing', 'slug' => 'testing', 'postCount' => 1],
            ['id' => 19, 'name' => 'phpunit', 'slug' => 'phpunit', 'postCount' => 1],
        ];
    }

    /**
     * @return list<array{
     *     id: int, name: string, email: string, role: string, status: string,
     *     statusLabel: string, initials: string, posts: int, joined: string,
     * }>
     */
    public static function users(): array
    {
        return [
            ['id' => 1, 'name' => 'Ada Lovelace', 'email' => 'ada@serixa.dev', 'role' => 'Admin', 'status' => 'success', 'statusLabel' => 'Active', 'initials' => 'AL', 'posts' => 3, 'joined' => '2025-11-02'],
            ['id' => 2, 'name' => 'Grace Hopper', 'email' => 'grace@serixa.dev', 'role' => 'Editor', 'status' => 'success', 'statusLabel' => 'Active', 'initials' => 'GH', 'posts' => 2, 'joined' => '2025-12-14'],
            ['id' => 3, 'name' => 'Alan Turing', 'email' => 'alan@serixa.dev', 'role' => 'Author', 'status' => 'success', 'statusLabel' => 'Active', 'initials' => 'AT', 'posts' => 2, 'joined' => '2026-01-20'],
            ['id' => 4, 'name' => 'Margaret Hamilton', 'email' => 'margaret@serixa.dev', 'role' => 'Author', 'status' => 'success', 'statusLabel' => 'Active', 'initials' => 'MH', 'posts' => 1, 'joined' => '2026-02-09'],
            ['id' => 5, 'name' => 'Katherine Johnson', 'email' => 'katherine@serixa.dev', 'role' => 'Contributor', 'status' => 'warning', 'statusLabel' => 'Invited', 'initials' => 'KJ', 'posts' => 1, 'joined' => '2026-06-30'],
            ['id' => 6, 'name' => 'Hedy Lamarr', 'email' => 'hedy@serixa.dev', 'role' => 'Subscriber', 'status' => 'neutral', 'statusLabel' => 'Suspended', 'initials' => 'HL', 'posts' => 0, 'joined' => '2026-03-11'],
        ];
    }

    /**
     * @return array{
     *     id: int, name: string, email: string, role: string, status: string,
     *     statusLabel: string, initials: string, posts: int, joined: string,
     * }|null
     */
    public static function user(int $id): ?array
    {
        foreach (self::users() as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @return list<array{
     *     id: int, postId: int, postTitle: string, author: string, authorEmail: string,
     *     body: string, status: string, statusLabel: string, createdAt: string,
     * }>
     */
    public static function comments(): array
    {
        return [
            ['id' => 1, 'postId' => 1, 'postTitle' => 'Announcing SERIXA', 'author' => 'Rosalind Franklin', 'authorEmail' => 'rosalind@example.com', 'body' => 'This is exactly the server-rendered approach I have been missing. Congrats on the release!', 'status' => 'approved', 'statusLabel' => 'Approved', 'createdAt' => '2026-06-02 14:20'],
            ['id' => 2, 'postId' => 1, 'postTitle' => 'Announcing SERIXA', 'author' => 'Marie Curie', 'authorEmail' => 'marie@example.com', 'body' => 'How does this compare to Livewire in terms of interactivity?', 'status' => 'approved', 'statusLabel' => 'Approved', 'createdAt' => '2026-06-02 16:05'],
            ['id' => 3, 'postId' => 2, 'postTitle' => 'Building Accessible Forms Without JavaScript', 'author' => 'Tim Berners-Lee', 'authorEmail' => 'tim@example.com', 'body' => 'Great to see aria-describedby handled by default instead of as an afterthought.', 'status' => 'approved', 'statusLabel' => 'Approved', 'createdAt' => '2026-06-11 09:41'],
            ['id' => 4, 'postId' => 6, 'postTitle' => 'Server-Rendered Widgets vs. Client Frameworks', 'author' => 'Guest User', 'authorEmail' => 'guest@example.com', 'body' => 'Curious what your Lighthouse scores looked like for the comparison demo.', 'status' => 'pending', 'statusLabel' => 'Pending', 'createdAt' => '2026-07-02 11:12'],
            ['id' => 5, 'postId' => 6, 'postTitle' => 'Server-Rendered Widgets vs. Client Frameworks', 'author' => 'buy-followers-now', 'authorEmail' => 'spam@example.com', 'body' => 'Check out my link for cheap followers!!!', 'status' => 'spam', 'statusLabel' => 'Spam', 'createdAt' => '2026-07-02 12:00'],
            ['id' => 6, 'postId' => 9, 'postTitle' => 'Testing Server-Rendered Widgets with PHPUnit', 'author' => 'Barbara Liskov', 'authorEmail' => 'barbara@example.com', 'body' => 'The point about avoiding brittle full-HTML snapshots really resonates.', 'status' => 'pending', 'statusLabel' => 'Pending', 'createdAt' => '2026-07-08 20:33'],
            ['id' => 7, 'postId' => 4, 'postTitle' => 'How We Ship Icons Without a Build Step', 'author' => 'Radia Perlman', 'authorEmail' => 'radia@example.com', 'body' => 'Inline SVG plus caching headers is such an underrated combo.', 'status' => 'approved', 'statusLabel' => 'Approved', 'createdAt' => '2026-06-26 08:15'],
        ];
    }

    /**
     * @return list<array{
     *     id: int, filename: string, type: string, size: string, uploadedBy: string, uploadedAt: string,
     * }>
     */
    public static function media(): array
    {
        return [
            ['id' => 1, 'filename' => 'serixa-release-banner.png', 'type' => 'image', 'size' => '482 KB', 'uploadedBy' => 'Ada Lovelace', 'uploadedAt' => '2026-06-01'],
            ['id' => 2, 'filename' => 'form-accessibility-diagram.svg', 'type' => 'image', 'size' => '96 KB', 'uploadedBy' => 'Grace Hopper', 'uploadedAt' => '2026-06-09'],
            ['id' => 3, 'filename' => 'theming-tokens-chart.png', 'type' => 'image', 'size' => '210 KB', 'uploadedBy' => 'Ada Lovelace', 'uploadedAt' => '2026-06-17'],
            ['id' => 4, 'filename' => 'icon-catalog-sheet.pdf', 'type' => 'document', 'size' => '1.1 MB', 'uploadedBy' => 'Alan Turing', 'uploadedAt' => '2026-06-24'],
            ['id' => 5, 'filename' => 'admin-widget-tour.mp4', 'type' => 'video', 'size' => '18.4 MB', 'uploadedBy' => 'Grace Hopper', 'uploadedAt' => '2026-07-13'],
            ['id' => 6, 'filename' => 'roadmap-v1.pdf', 'type' => 'document', 'size' => '640 KB', 'uploadedBy' => 'Ada Lovelace', 'uploadedAt' => '2026-07-19'],
            ['id' => 7, 'filename' => 'testing-strategy-cover.png', 'type' => 'image', 'size' => '312 KB', 'uploadedBy' => 'Alan Turing', 'uploadedAt' => '2026-07-08'],
            ['id' => 8, 'filename' => 'author-headshot-hamilton.jpg', 'type' => 'image', 'size' => '154 KB', 'uploadedBy' => 'Margaret Hamilton', 'uploadedAt' => '2026-07-01'],
        ];
    }
}
