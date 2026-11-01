<?php

declare(strict_types=1);

namespace App\Tests;

use App\Support\Markdown;
use PHPUnit\Framework\TestCase;

final class MarkdownPolishTest extends TestCase
{
    public function testCodeBlocksIncludeCopyButton(): void
    {
        $html = Markdown::toHtml("```php\necho 1;\n```");
        $this->assertStringContainsString('doc-code', $html);
        $this->assertStringContainsString('data-serixa-copy', $html);
        $this->assertStringContainsString('Copy', $html);
        $this->assertStringContainsString('echo 1;', $html);
    }

    public function testHeadingsAndLinks(): void
    {
        $html = Markdown::toHtml("# Title\n\nSee [docs](https://example.com).");
        $this->assertStringContainsString('<h1>Title</h1>', $html);
        $this->assertStringContainsString('href="https://example.com"', $html);
    }

    public function testRepositoryMarkdownLinksResolveToPublicDestinations(): void
    {
        $root = dirname(__DIR__, 2);

        $assets = Markdown::fileToHtml($root . '/docs/assets.md');
        $this->assertStringContainsString('href="?page=docs-styling"', $assets);

        $admin = Markdown::fileToHtml($root . '/docs/admin.md');
        $this->assertStringContainsString(
            'href="https://github.com/YasasPasinduFernando/serixa/tree/main/packages/admin"',
            $admin,
        );
    }
}
