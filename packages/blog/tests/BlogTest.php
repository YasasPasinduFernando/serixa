<?php

declare(strict_types=1);

namespace Serixa\Blog\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Blog\Blog;
use Serixa\Cms\Cms;

final class BlogTest extends TestCase
{
    public function testPublishMirrorsToCms(): void
    {
        $cms = Cms::memory();
        $blog = Blog::memory($cms);
        $blog->addCategory('news', 'news', 'News');
        $post = $blog->createPost('p1', 'hello', 'Hello', 'intro', 'author-1')
            ->addCategory('news');
        $blog->writeBody($post, '# Hello world');
        $blog->publish($post);

        self::assertTrue($post->isPublished());
        self::assertCount(1, $blog->store()->published());
        self::assertNotNull($cms->store()->findBySlug('blog/hello'));
    }
}
