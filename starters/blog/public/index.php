<?php

declare(strict_types=1);

use App\Web;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = is_string($_GET['page'] ?? null) ? $_GET['page'] : 'dashboard';

if (! Web::isKnown($page)) {
    http_response_code(404);
}

$document = Web::document($page, $_GET);

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render($document), PHP_EOL;
