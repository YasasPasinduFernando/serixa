<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\Badge;
use Serixa\Component\Column;
use Serixa\Component\Image;
use Serixa\Component\Text;
use Serixa\Component\Tooltip;
use Serixa\Document\Document;

final class ArticlePage
{
    public static function document(): Document
    {
        $article = Column::make([
            Image::make()
                ->src('https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1400&q=85')
                ->alt('Code editor showing a server-rendered application')
                ->width(1400)->height(788)->loading('eager'),
            Badge::make('Engineering')->color('primary'),
            Text::make('Server-rendered interfaces that feel alive')
                ->as('h1')->size('2xl')->weight('bold'),
            Tooltip::make()
                ->text('About a six-minute read')
                ->child(Text::make('6 min read · July 18, 2026')->as('span')->size('sm')->tone('muted')),
            Text::make('The web works best when its foundations remain dependable. Server rendering gives every visitor useful HTML immediately, before JavaScript downloads or executes.'),
            Text::make('Progressive enhancement builds from that baseline. Navigation stays navigation, forms remain forms, and carefully chosen client behavior adds convenience without becoming a prerequisite.'),
            Text::make('This approach is not a retreat from rich interfaces. It is a way to make richness resilient: faster first paint, simpler failure modes, and an experience that respects every device and connection.'),
        ])->gap(4)->alignStart();

        return Document::make()
            ->title('Server-rendered interfaces that feel alive · SERIXA Journal')
            ->description('How progressive enhancement makes PHP applications resilient.')
            ->body(SiteLayout::shell('articles', $article));
    }
}
