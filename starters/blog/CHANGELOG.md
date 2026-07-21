# Changelog

All notable changes to the SERIXA Blog Starter are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [0.1.0] - 2026-07-22

### Added

- Initial release of the official SERIXA Blog Starter: a full blog CMS
  scaffold built entirely on `serixa/framework`, `serixa/admin` and
  `serixa/icons`, with no database, ORM, or authentication logic.
- Twelve pages: Dashboard, Posts, New Post, Edit Post, Categories, Tags,
  Media, Comments, Users, Profile, Settings, and a 404 Not Found page.
- `App\Data\Fixtures` static fake data (posts, categories, tags, users,
  comments, media) used across every page.
- `App\BlogLayout` shared shell (sidebar, top bar, footer) built on
  `Serixa\Admin\Layout\AdminLayout`.
- `App\Web` query-string page router and `public/index.php` front
  controller, including a real `404` status code for unknown pages.
- `App\DocumentFactory` for consistent document/SEO/asset setup, and
  `bin/sync-runtime.php` to refresh the copied client runtime script.
- Smoke tests covering every page route.
