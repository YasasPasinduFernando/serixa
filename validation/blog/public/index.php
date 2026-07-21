<?php

declare(strict_types=1);

use App\Layouts\SiteLayout;
use App\Pages\AboutPage;
use App\Pages\ArticlePage;
use App\Pages\ArticlesPage;
use App\Pages\CategoriesPage;
use App\Pages\ContactPage;
use App\Pages\HomePage;
use App\Pages\LoginPage;
use App\Pages\NotFoundPage;
use App\Pages\ProfilePage;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = strtolower((string) ($_GET['page'] ?? 'home'));

$document = match ($page) {
    'home' => HomePage::document(),
    'articles' => ArticlesPage::document(),
    'article' => ArticlePage::document(),
    'categories' => CategoriesPage::document(),
    'about' => AboutPage::document(),
    'contact' => ContactPage::document(),
    'login' => LoginPage::document(),
    'profile' => ProfilePage::document(),
    default => NotFoundPage::document(),
};

if (!in_array($page, ['home', 'articles', 'article', 'categories', 'about', 'contact', 'login', 'profile'], true)) {
    http_response_code(404);
}

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render(SiteLayout::wrap($document));
