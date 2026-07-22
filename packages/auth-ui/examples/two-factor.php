<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\TwoFactorCard;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$layout = AuthLayout::make()->card(
    TwoFactorCard::make()
        ->action('#')
        ->brand(BrandLogo::make('SERIXA'))
        ->title('Two-factor authentication')
        ->subtitle('Enter the code from your authenticator app. Demo UI only.'),
);

$document = Document::make()->title('Two-factor · SERIXA Auth UI')->body($layout);
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
