<?php

declare(strict_types=1);

use App\SiteContext;
use App\ThemeMode;
use App\Web;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = is_string($_GET['page'] ?? null) ? $_GET['page'] : 'home';
$queryTheme = is_string($_GET['theme'] ?? null) ? $_GET['theme'] : null;
$cookieTheme = is_string($_COOKIE[ThemeMode::COOKIE] ?? null) ? $_COOKIE[ThemeMode::COOKIE] : null;
$theme = ThemeMode::resolve($queryTheme, $cookieTheme);

if ($queryTheme !== null) {
    setcookie(ThemeMode::COOKIE, $theme, [
        'expires' => time() + 60 * 60 * 24 * 365,
        'path' => '/',
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
}

SiteContext::boot($page, $theme);

if (! Web::isKnown($page)) {
    http_response_code(404);
}

$document = Web::document($page, $_GET);

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render($document), PHP_EOL;
