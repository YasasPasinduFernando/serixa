<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Blog\Blog;
use Serixa\Cms\Cms;

$cms = Cms::memory();
$blog = Blog::memory($cms);
$blog->addCategory('eng', 'engineering', 'Engineering');
$post = $blog->createPost('1', 'serixa-cms', 'SERIXA CMS', 'Phase 3', 'yasas')->addCategory('eng');
$blog->writeBody($post, 'Shipping CMS packages.');
$blog->publish($post);

echo 'posts: ', count($blog->store()->published()), PHP_EOL;
echo 'cms pages: ', count($cms->store()->published()), PHP_EOL;

if (count($blog->store()->published()) === 1 && $cms->store()->findBySlug('blog/serixa-cms') !== null) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
