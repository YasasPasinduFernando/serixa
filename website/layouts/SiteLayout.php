<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Divider;
use Serixa\Component\Footer;
use Serixa\Component\Link;
use Serixa\Component\Text;
use Serixa\Contracts\ComponentInterface;
use Serixa\Document\Document;

/**
 * Marketing / landing chrome: top nav + content + footer.
 */
final class SiteLayout
{
    private function __construct()
    {
    }

    public static function wrap(string $active, ComponentInterface $content, string $title = ''): Document
    {
        $theme = SiteContext::theme();

        $shell = Container::make(
            Column::make([
                Container::make(SiteNav::make($active, $theme))->class('site-header'),
                Container::make($content)->class('site-main'),
                Divider::make(),
                Footer::make(
                    Column::make([
                        Text::make('SERIXA — Modern SSR UI for PHP')->size('sm')->tone('muted'),
                        Link::make('GitHub')->href('https://github.com/YasasPasinduFernando/serixa'),
                    ])->gap(2),
                ),
            ])->gap(0)->alignStretch(),
        )->class('site-shell')->attribute('data-theme', $theme);

        return DocumentFactory::make($title, $shell);
    }
}
