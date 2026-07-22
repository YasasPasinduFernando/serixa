<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Assets\StyleSheet;

use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Layout\AuthAside;
use Serixa\AuthUi\Layout\AuthIllustration;
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\MagicLinkCard;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$layout = AuthLayout::make()
    ->aside(AuthAside::make('Passwordless sign-in', 'Email a one-time link. Your app sends the mail.'))
    ->illustration(AuthIllustration::make('No password to remember.'))
    ->content(
        MagicLinkCard::make()
            ->action('#')
            ->brand(BrandLogo::make('SERIXA'))
            ->title('Email me a magic link')
            ->subtitle('Demo UI only — no email is sent.'),
    );

$document = Document::make()->title('Magic link · SERIXA Auth UI')->body($layout);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
