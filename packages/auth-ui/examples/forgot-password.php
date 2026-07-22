<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\ForgotPasswordCard;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$layout = AuthLayout::make()->card(
    ForgotPasswordCard::make()
        ->action('#')
        ->brand(BrandLogo::make('SERIXA'))
        ->title('Forgot password')
        ->subtitle('We will email you a reset link. Demo UI only.'),
);

$document = Document::make()->title('Forgot password · SERIXA Auth UI')->body($layout);
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
