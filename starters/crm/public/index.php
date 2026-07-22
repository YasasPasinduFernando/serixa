<?php

declare(strict_types=1);

use App\Web;
use Serixa\Admin\Theme\AdminTheme;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

require dirname(__DIR__) . '/vendor/autoload.php';

ThemeManager::reset();
IconRegistry::reset();
ThemeManager::set(new AdminTheme(new DefaultTheme()));

$page = is_string($_GET['page'] ?? null) ? $_GET['page'] : 'dashboard';

if (! Web::isKnown($page)) {
    http_response_code(404);
}

$document = Web::document($page, $_GET);

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render($document), PHP_EOL;
