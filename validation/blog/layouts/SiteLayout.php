<?php

declare(strict_types=1);

namespace App\Layouts;

use App\Components\SiteNav;
use Serixa\Component\AppBar;
use Serixa\Component\Container;
use Serixa\Component\Footer;
use Serixa\Component\Logo;
use Serixa\Component\Scaffold;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

final class SiteLayout
{
    public static function wrap(Document $document): Document
    {
        $document->assets()
            ->js('https://cdn.tailwindcss.com')
            ->css('/app.css');

        ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');

        return $document;
    }

    public static function shell(string $active, WidgetInterface $content): WidgetInterface
    {
        $bar = AppBar::make(Logo::make()->text('SERIXA Journal')->href('/?page=home'))
            ->actions(SiteNav::make($active));

        $footer = Footer::make(
            Text::make('© 2026 SERIXA Journal. Stories about thoughtful software.')
                ->size('sm')
                ->tone('muted'),
        );

        return Scaffold::make()
            ->appBar($bar)
            ->body(Container::make($content)->maxWidth('xl')->padding('lg'))
            ->footer($footer);
    }
}
