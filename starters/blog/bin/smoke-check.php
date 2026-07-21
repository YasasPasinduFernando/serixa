<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Web;
use Serixa\Rendering\Renderer;

$pages = [
    'dashboard', 'posts', 'create-post', 'edit-post', 'categories', 'tags',
    'media', 'comments', 'users', 'profile', 'settings', 'totally-unknown-page',
];

$renderer = new Renderer();
$_GET['id'] = 1;

foreach ($pages as $page) {
    $document = Web::document($page);
    $html = $renderer->render($document);
    $hasHtml = str_contains($html, '<html');

    printf(
        "%-22s known=%-5s bytes=%-7d hasHtml=%s%s",
        $page,
        Web::isKnown($page) ? 'yes' : 'no',
        strlen($html),
        $hasHtml ? 'yes' : 'no',
        PHP_EOL,
    );
}

echo 'ALL PAGES RENDERED OK' . PHP_EOL;
