<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Styling\StyleSheet;

use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\ResetPasswordCard;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$layout = AuthLayout::make()->card(
    ResetPasswordCard::make()
        ->action('#')
        ->brand(BrandLogo::make('SERIXA'))
        ->title('Reset password')
        ->subtitle('Choose a new password. Demo UI only.'),
);

$document = Document::make()->title('Reset password · SERIXA Auth UI')->body($layout);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
