<?php

declare(strict_types=1);

namespace Serixa\Cms\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Cms\Cms;
use Serixa\Cms\ContentBlock;
use Serixa\Cms\ContentStatus;

final class CmsTest extends TestCase
{
    public function testCreatePublishAndFind(): void
    {
        $cms = Cms::memory();
        $page = $cms->create('p1', 'about', 'About')
            ->addBlock(ContentBlock::make('b1', 'markdown', '# Hello'))
            ->publish();

        self::assertSame(ContentStatus::Published, $page->status());
        self::assertNotNull($cms->store()->findBySlug('about'));
        self::assertCount(1, $cms->store()->published());
    }
}
