<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Component;
use Serixa\Component\Image;
use Serixa\Component\Link;
use Serixa\Component\Row;

/**
 * Top site navigation — brand, primary links, search placeholder, theme toggle.
 */
final class SiteNav
{
    private function __construct()
    {
    }

    public static function make(string $active, string $theme): Component
    {
        $link = static function (string $label, string $page, string $activePage): Link {
            $isActive = $activePage === $page
                || ($page === 'docs-introduction' && str_starts_with($activePage, 'docs-'))
                || ($page === 'examples-blog' && str_starts_with($activePage, 'examples-'));

            return Link::make($label)
                ->href('?page=' . $page)
                ->active($isActive)
                ->class('site-nav__link');
        };

        return Row::make([
            Row::make([
                Image::make()
                    ->src('/assets/brand/icon-128x128.png')
                    ->alt('')
                    ->width(128)
                    ->height(128)
                    ->class('site-nav__logo'),
                Link::make('SERIXA')->href('?page=home')->class('site-nav__brand'),
            ])->gap(2)->alignCenter()->class('site-nav__brand-lockup'),
            Row::make([
                $link('Docs', 'docs-introduction', $active),
                $link('Packages', 'packages', $active),
                $link('Examples', 'examples-blog', $active),
                $link('Playground', 'playground', $active),
            ])->gap(4)->class('site-nav__links'),
            Row::make([
                Link::make($theme === 'dark' ? 'Light' : 'Dark')
                    ->href(ThemeMode::toggleUrl($theme, $active === '' ? 'home' : $active))
                    ->class('site-nav__theme'),
                Link::make('GitHub ↗')
                    ->href('https://github.com/YasasPasinduFernando/serixa')
                    ->class('site-nav__github'),
            ])->gap(3)->alignCenter()->class('site-nav__actions'),
        ])->spaceBetween()->alignCenter()->class('site-nav');
    }
}
