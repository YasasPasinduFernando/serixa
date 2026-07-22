<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Cms\Cms;
use Serixa\Cms\ContentBlock;

$cms = Cms::memory();
$page = $cms->create('home', 'home', 'Home')->addBlock(ContentBlock::make('hero', 'html', '<h1>Hi</h1>'))->publish();

echo 'slug: ', $page->slug(), PHP_EOL;
echo 'published: ', count($cms->store()->published()), PHP_EOL;

if ($page->isPublished() && count($cms->store()->published()) === 1) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
