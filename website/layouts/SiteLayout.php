<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Divider;
use Serixa\Component\Footer;
use Serixa\Component\Image;
use Serixa\Component\Link;
use Serixa\Component\Row;
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

        // Full-bleed shell (no Container max-width) so header/nav are not squeezed.
        $shell = Column::make([
            Link::make('Skip to content')->href('#main-content')->class('skip-link'),
            SiteNav::make($active, $theme)->class('site-header'),
            Container::make($content)->maxWidth('xl')->class('site-main')->id('main-content')->attribute('role', 'main'),
            Divider::make(),
            Footer::make(
                Container::make(
                    Column::make([
                        Row::make([
                            Row::make([
                                Image::make()
                                    ->src('/assets/brand/icon-128x128.png')
                                    ->alt('')
                                    ->width(128)
                                    ->height(128)
                                    ->class('footer-logo'),
                                Column::make([
                                    Text::make('SERIXA')->weight('bold'),
                                    Text::make('Flutter-inspired PHP UI engine')->size('sm')->tone('muted'),
                                ])->gap(1),
                            ])->gap(3)->alignCenter(),
                            Row::make([
                                Link::make('Documentation')->href('https://serixa.yasaboy.com'),
                                Link::make('GitHub')->href('https://github.com/YasasPasinduFernando/serixa'),
                                Link::make('Security')->href('https://github.com/YasasPasinduFernando/serixa/blob/main/SECURITY.md'),
                            ])->gap(4)->class('footer-links'),
                        ])->spaceBetween()->alignCenter()->class('footer-main'),
                        Text::make('Built with SERIXA. Rendered as semantic HTML.')->size('sm')->tone('muted'),
                    ])->gap(5),
                )->maxWidth('xl')->padding('none'),
            )->class('site-footer')->attribute('role', 'contentinfo'),
        ])
            ->gap(0)
            ->alignStretch()
            ->fill()
            ->class($theme === 'dark' ? 'site-shell dark' : 'site-shell')
            ->attribute('data-theme', $theme);

        $path = $active === 'home' || $active === ''
            ? '/'
            : '/?page=' . rawurlencode($active);

        return DocumentFactory::make($title, $shell, '', $path);
    }
}
