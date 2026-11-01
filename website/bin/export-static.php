<?php

declare(strict_types=1);

/**
 * Pre-render the SERIXA docs site to static HTML for Cloudflare Pages.
 *
 *     php bin/export-static.php
 */

use App\SiteContext;
use App\Web;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
$dist = $root . '/dist';
$public = $root . '/public';

function rimraf(string $path): void
{
    if (! file_exists($path)) {
        return;
    }
    if (is_file($path) || is_link($path)) {
        unlink($path);

        return;
    }
    $items = scandir($path);
    if ($items === false) {
        return;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        rimraf($path . DIRECTORY_SEPARATOR . $item);
    }
    rmdir($path);
}

function copyTree(string $src, string $dest): void
{
    if (is_file($src)) {
        $dir = dirname($dest);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        copy($src, $dest);

        return;
    }
    if (! is_dir($dest)) {
        mkdir($dest, 0775, true);
    }
    $items = scandir($src);
    if ($items === false) {
        return;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        copyTree($src . DIRECTORY_SEPARATOR . $item, $dest . DIRECTORY_SEPARATOR . $item);
    }
}

/**
 * Rewrite ?page= query links to path-style URLs for static hosting.
 */
function rewriteStaticLinks(string $html): string
{
    $out = preg_replace_callback(
        '/\b(href|action)=(["\'])(\/?\?[^"\']*)\2/i',
        static function (array $m): string {
            $attr = $m[1];
            $quote = $m[2];
            $raw = $m[3];
            $query = ltrim($raw, '/');
            if (! str_starts_with($query, '?')) {
                return $m[0];
            }
            $query = substr($query, 1);
            $query = html_entity_decode($query, ENT_QUOTES | ENT_HTML5);
            [$query, $fragment] = array_pad(explode('#', $query, 2), 2, '');
            parse_str($query, $params);
            if (! isset($params['page']) || ! is_string($params['page'])) {
                return $m[0];
            }
            $page = $params['page'];
            unset($params['page']);
            $path = ($page === 'home' || $page === '') ? '/' : '/' . rawurlencode($page) . '/';
            if ($params !== []) {
                $path .= '?' . http_build_query($params);
            }
            if ($fragment !== '') {
                $path .= '#' . $fragment;
            }

            return $attr . '=' . $quote . $path . $quote;
        },
        $html
    );

    return is_string($out) ? $out : $html;
}

/**
 * Keep canonical, Open Graph, and JSON-LD URLs aligned with exported routes.
 */
function rewriteProductionPageUrls(string $html): string
{
    $out = preg_replace_callback(
        '#https://serixa\.yasaboy\.com/\?page=([a-z0-9_-]+)#i',
        static function (array $match): string {
            $page = rawurldecode($match[1]);

            return $page === '' || $page === 'home'
                ? 'https://serixa.yasaboy.com/'
                : 'https://serixa.yasaboy.com/' . rawurlencode($page) . '/';
        },
        $html,
    );

    return is_string($out) ? $out : $html;
}

/**
 * Inject client-side theme runtime for Cloudflare Pages (no PHP request boot).
 */
function injectThemeStaticScript(string $html): string
{
    $tag = '<script src="/assets/runtime/theme-static.js"></script>';
    if (str_contains($html, $tag) || str_contains($html, 'theme-static.js')) {
        return $html;
    }
    $pos = strripos($html, '</body>');
    if ($pos === false) {
        return $html . "\n" . $tag . "\n";
    }

    return substr($html, 0, $pos) . $tag . "\n" . substr($html, $pos);
}

rimraf($dist);
mkdir($dist, 0775, true);

$routes = Web::allRoutes();
if (! in_array('home', $routes, true)) {
    array_unshift($routes, 'home');
}

$renderer = new Renderer();
$written = 0;

foreach ($routes as $page) {
    SiteContext::boot($page, 'light');
    $document = Web::document($page, ['page' => $page]);
    $html = $renderer->render($document) . "\n";
    $html = rewriteStaticLinks($html);
    $html = rewriteProductionPageUrls($html);
    $html = injectThemeStaticScript($html);

    if ($page === 'home') {
        $target = $dist . '/index.html';
    } else {
        $dir = $dist . '/' . $page;
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $target = $dir . '/index.html';
    }

    file_put_contents($target, $html);
    $written++;
    echo "Wrote {$target}\n";
}

// Static assets
if (is_dir($public . '/assets')) {
    copyTree($public . '/assets', $dist . '/assets');
    echo "Copied public/assets → dist/assets\n";
}

foreach (['robots.txt', 'favicon.svg'] as $file) {
    $src = $public . '/' . $file;
    if (is_file($src)) {
        copy($src, $dist . '/' . $file);
        echo "Copied {$file}\n";
    }
}

$sitemapEntries = [];
foreach ($routes as $page) {
    $path = $page === 'home' ? '/' : '/' . rawurlencode($page) . '/';
    $priority = match (true) {
        $page === 'home' => '1.0',
        $page === 'components', $page === 'packages' => '0.9',
        str_starts_with($page, 'docs-') => '0.8',
        default => '0.6',
    };
    $location = htmlspecialchars('https://serixa.yasaboy.com' . $path, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    $sitemapEntries[] = sprintf(
        '  <url><loc>%s</loc><changefreq>weekly</changefreq><priority>%s</priority></url>',
        $location,
        $priority,
    );
}
$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
    . "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n"
    . implode("\n", $sitemapEntries)
    . "\n</urlset>\n";
file_put_contents($dist . '/sitemap.xml', $sitemap);
echo sprintf("Wrote sitemap.xml (%d path-style routes)\n", count($routes));

// Cloudflare Pages ignores query strings when matching _redirects, so rules like
// "/?page=packages /packages/" are treated as "/ → /packages/" and break the homepage.
// Path-style URLs (/packages/, etc.) are already static directories — no redirects needed.
// Legacy ?page= URLs are handled by Apache .htaccess on PHP hosts only.
file_put_contents(
    $dist . '/_redirects',
    "# No query-string redirects — Cloudflare Pages cannot match ?page= safely.\n"
);
echo "Wrote _redirects (empty ruleset)\n";

file_put_contents(
    $dist . '/_headers',
    <<<'HEADERS'
/*
  X-Content-Type-Options: nosniff
  Referrer-Policy: strict-origin-when-cross-origin
  Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()
  X-Frame-Options: DENY
  X-Permitted-Cross-Domain-Policies: none
  Strict-Transport-Security: max-age=31536000; includeSubDomains
  Cross-Origin-Opener-Policy: same-origin
  Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data: https:; font-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; connect-src 'self'

/assets/*
  Cache-Control: public, max-age=604800, stale-while-revalidate=86400

HEADERS
);
echo "Wrote _headers (security + asset caching)\n";

echo sprintf(
    "\nStatic export complete: %d pages → %s\n",
    $written,
    $dist
);
