<?php

declare(strict_types=1);

namespace App;

/**
 * Official package catalog shown on the Packages page and homepage.
 *
 * Tiers: Core · UI Kits · Developer Tools · Official · Experimental.
 * Experimental packages are client-port adapters (Fake* for offline QA).
 *
 * @phpstan-type PackageEntry array{
 *     name: string,
 *     title: string,
 *     description: string,
 *     href: string,
 *     tier: 'core'|'ui'|'devtools'|'official'|'experimental',
 *     maturity: 'stable'|'v0.1'|'experimental'
 * }
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
        return array_merge(
            self::core(),
            self::uiKits(),
            self::devtools(),
            self::official(),
            self::experimental(),
        );
    }

    /**
     * Featured packages for the homepage (core + UI kits + tools).
     *
     * @return list<PackageEntry>
     */
    public static function featured(int $limit = 6): array
    {
        return array_slice(array_merge(self::core(), self::uiKits(), self::devtools()), 0, $limit);
    }

    /**
     * @return array<string, list<PackageEntry>>
     */
    public static function grouped(): array
    {
        $groups = [
            'Core' => [],
            'UI Kits' => [],
            'Developer Tools' => [],
            'Official Packages' => [],
            'Experimental Packages' => [],
        ];

        foreach (self::all() as $package) {
            $label = match ($package['tier']) {
                'core' => 'Core',
                'ui' => 'UI Kits',
                'devtools' => 'Developer Tools',
                'official' => 'Official Packages',
                'experimental' => 'Experimental Packages',
            };
            $groups[$label][] = $package;
        }

        return $groups;
    }

    /**
     * @return list<PackageEntry>
     */
    private static function core(): array
    {
        return [
            self::entry('serixa/framework', 'Framework', 'SSR widget engine — Document, Renderer, ThemeManager, StyleSheet, client runtime.', DocsCatalog::href('introduction'), 'core', 'stable'),
        ];
    }

    /**
     * @return list<PackageEntry>
     */
    private static function uiKits(): array
    {
        return [
            self::entry('serixa/icons', 'Icons', 'Official icon set with theme-aware SVG widgets.', DocsCatalog::href('icons'), 'ui', 'v0.1'),
            self::entry('serixa/forms', 'Forms', 'Form builder, binding, and validation helpers for SERIXA UIs.', DocsCatalog::href('forms'), 'ui', 'v0.1'),
            self::entry('serixa/admin', 'Admin', 'Admin shell, sidebar, top bar, and panel layouts.', DocsCatalog::href('admin'), 'ui', 'v0.1'),
            self::entry('serixa/charts', 'Charts', 'SSR SVG charts with progressive enhancement — UI only.', DocsCatalog::href('charts'), 'ui', 'v0.1'),
            self::entry('serixa/calendar', 'Calendar', 'Month, week, day, and agenda calendar widgets.', DocsCatalog::href('calendar'), 'ui', 'v0.1'),
            self::entry('serixa/auth-ui', 'Auth UI', 'Login, register, and profile UI surfaces — HTML only (no sessions).', DocsCatalog::href('packages'), 'ui', 'v0.1'),
        ];
    }

    /**
     * @return list<PackageEntry>
     */
    private static function devtools(): array
    {
        return [
            self::entry('serixa/cli', 'CLI', 'Scaffolding: serixa new, make:*, build, docs, doctor, profile.', DocsCatalog::href('cli'), 'devtools', 'v0.1'),
            self::entry('serixa/devtools', 'DevTools', 'Development-only inspectors, collectors, and overlays.', DocsCatalog::href('devtools'), 'devtools', 'v0.1'),
        ];
    }

    /**
     * Domain packages with real in-memory / pure-PHP implementations.
     *
     * @return list<PackageEntry>
     */
    private static function official(): array
    {
        $eco = '?page=packages';

        return [
            self::entry('serixa/rbac', 'RBAC', 'Roles and permissions primitives.', $eco, 'official', 'v0.1'),
            self::entry('serixa/permissions', 'Permissions', 'Permission checks built on RBAC.', $eco, 'official', 'v0.1'),
            self::entry('serixa/organization', 'Organization', 'Multi-tenant organization model.', $eco, 'official', 'v0.1'),
            self::entry('serixa/teams', 'Teams', 'Teams within organizations.', $eco, 'official', 'v0.1'),
            self::entry('serixa/activity-log', 'Activity Log', 'Activity / event logging.', $eco, 'official', 'v0.1'),
            self::entry('serixa/audit', 'Audit', 'Audit trail built on activity-log.', $eco, 'official', 'v0.1'),
            self::entry('serixa/cart', 'Cart', 'Shopping cart domain model.', $eco, 'official', 'v0.1'),
            self::entry('serixa/checkout', 'Checkout', 'Turn carts into orders.', $eco, 'official', 'v0.1'),
            self::entry('serixa/invoice', 'Invoice', 'Invoice documents.', $eco, 'official', 'v0.1'),
            self::entry('serixa/tax', 'Tax', 'Tax calculation helpers.', $eco, 'official', 'v0.1'),
            self::entry('serixa/subscriptions', 'Subscriptions', 'Subscription plans and billing cycles.', $eco, 'official', 'v0.1'),
            self::entry('serixa/cms', 'CMS', 'Content pages and publishing primitives.', $eco, 'official', 'v0.1'),
            self::entry('serixa/menu', 'Menu', 'Navigation menu builders.', $eco, 'official', 'v0.1'),
            self::entry('serixa/seo', 'SEO', 'Helpers that apply onto framework SeoManager.', $eco, 'official', 'v0.1'),
            self::entry('serixa/sitemap', 'Sitemap', 'XML sitemap generation.', $eco, 'official', 'v0.1'),
            self::entry('serixa/blog', 'Blog', 'Blog posts on top of CMS.', $eco, 'official', 'v0.1'),
            self::entry('serixa/local-storage', 'Local Storage', 'Storage contract + local / memory drivers.', $eco, 'official', 'v0.1'),
            self::entry('serixa/media-library', 'Media Library', 'Media attachments on storage.', $eco, 'official', 'v0.1'),
            self::entry('serixa/file-manager', 'File Manager', 'Browse and manage stored files.', $eco, 'official', 'v0.1'),
            self::entry('serixa/pdf', 'PDF', 'PDF document helpers.', $eco, 'official', 'v0.1'),
            self::entry('serixa/excel', 'Excel', 'Spreadsheet helpers.', $eco, 'official', 'v0.1'),
            self::entry('serixa/word', 'Word', 'Word document helpers.', $eco, 'official', 'v0.1'),
            self::entry('serixa/zip', 'Zip', 'Zip archive create / extract.', $eco, 'official', 'v0.1'),
            self::entry('serixa/passkeys', 'Passkeys', 'Passkey credential and challenge management.', $eco, 'official', 'v0.1'),
            self::entry('serixa/webauthn', 'WebAuthn', 'Ceremony options builders on passkeys.', $eco, 'official', 'v0.1'),
            self::entry('serixa/two-factor', 'Two-Factor', 'Pure PHP TOTP verifier.', $eco, 'official', 'v0.1'),
        ];
    }

    /**
     * Client-port adapters — Fake* for tests; inject real HTTP/SDK clients in apps.
     *
     * @return list<PackageEntry>
     */
    private static function experimental(): array
    {
        $eco = '?page=packages';
        $note = 'Experimental v0.1.0 — injectable client port + Fake* for offline QA.';

        $names = [
            'aws-s3' => 'AWS S3 storage adapter.',
            'cloudinary' => 'Cloudinary storage adapter.',
            'google-drive' => 'Google Drive storage adapter.',
            'dropbox' => 'Dropbox storage adapter.',
            'recaptcha' => 'reCAPTCHA verifier port.',
            'hcaptcha' => 'hCaptcha verifier port.',
            'meilisearch' => 'SearchEngine contract + Meilisearch adapter.',
            'algolia' => 'Algolia SearchEngine adapter.',
            'typesense' => 'Typesense SearchEngine adapter.',
            'elasticsearch' => 'Elasticsearch SearchEngine adapter.',
            'openai' => 'LanguageModel contract + OpenAI adapter.',
            'gemini' => 'Gemini LanguageModel adapter.',
            'anthropic' => 'Anthropic LanguageModel adapter.',
            'deepseek' => 'DeepSeek LanguageModel adapter.',
            'ollama' => 'Ollama LanguageModel adapter.',
            'firebase' => 'Notifier contract + FCM adapter.',
            'onesignal' => 'OneSignal Notifier adapter.',
            'twilio' => 'Twilio SMS Notifier adapter.',
            'slack' => 'Slack Notifier adapter.',
            'discord' => 'Discord webhook Notifier adapter.',
            'telegram' => 'Telegram Bot Notifier adapter.',
            'whatsapp' => 'WhatsApp Cloud Notifier adapter.',
            'google-calendar' => 'CalendarProvider contract + Google adapter.',
            'outlook-calendar' => 'Outlook CalendarProvider adapter.',
            'ical' => 'iCalendar CalendarProvider adapter.',
            'caldav' => 'CalDAV CalendarProvider adapter.',
            'google-oauth' => 'OAuthProvider contract + Google adapter.',
            'github-oauth' => 'GitHub OAuthProvider adapter.',
            'facebook-oauth' => 'Facebook OAuthProvider adapter.',
            'microsoft-oauth' => 'Microsoft OAuthProvider adapter.',
            'stripe' => 'PaymentGateway contract + Stripe adapter.',
            'paypal' => 'PayPal PaymentGateway adapter.',
            'payhere' => 'PayHere PaymentGateway adapter.',
            'koko' => 'Koko PaymentGateway adapter.',
        ];

        $out = [];
        foreach ($names as $dir => $desc) {
            $title = ucwords(str_replace('-', ' ', $dir));
            $out[] = self::entry('serixa/' . $dir, $title, $note . ' ' . $desc, $eco, 'experimental', 'experimental');
        }

        return $out;
    }

    /**
     * @param 'core'|'ui'|'devtools'|'official'|'experimental' $tier
     * @param 'stable'|'v0.1'|'experimental' $maturity
     * @return PackageEntry
     */
    private static function entry(
        string $name,
        string $title,
        string $description,
        string $href,
        string $tier,
        string $maturity,
    ): array {
        return [
            'name' => $name,
            'title' => $title,
            'description' => $description,
            'href' => $href,
            'tier' => $tier,
            'maturity' => $maturity,
        ];
    }
}
