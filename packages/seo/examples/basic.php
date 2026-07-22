<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Document\Document;
use Serixa\Seo\SeoMeta;

$meta = SeoMeta::make('SERIXA', 'UI framework')
    ->canonical('https://serixa.dev')
    ->keywords(['php', 'ui'])
    ->openGraph('og:type', 'website');

$doc = Document::make();
$meta->applyToDocument($doc);

echo 'title: ', $meta->getTitle(), PHP_EOL;

if ($meta->getTitle() === 'SERIXA') {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
