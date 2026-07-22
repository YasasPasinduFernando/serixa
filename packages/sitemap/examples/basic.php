<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Sitemap\Sitemap;
use Serixa\Sitemap\SitemapUrl;

$xml = Sitemap::make()
    ->add(SitemapUrl::make('https://serixa.dev/'))
    ->add(SitemapUrl::make('https://serixa.dev/docs', null, 'monthly', 0.8))
    ->toXml();

echo $xml;

if (str_contains($xml, 'serixa.dev/docs') && str_contains($xml, '</urlset>')) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
