<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class AboutPage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()->title('About the Journal')->subtitle('A publication for people who make software.'),
            Text::make('SERIXA Journal shares practical lessons from engineers, designers, writers, and product builders. We focus on ideas that remain useful after the trend cycle moves on.'),
            Text::make('Our authors value clear communication, accessible interfaces, resilient architecture, and sustainable ways of working. Every story aims to leave you with something concrete to try.'),
            Text::make('The journal is an independent validation application built entirely with the public SERIXA framework API.')->tone('muted'),
        ])->gap(4)->alignStart();

        return Document::make()
            ->title('About · SERIXA Journal')
            ->description('Learn about SERIXA Journal and its editorial mission.')
            ->body(SiteLayout::shell('about', $content));
    }
}
