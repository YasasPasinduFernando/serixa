<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Styling\StyleSheet;

use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Components\PasswordRequirements;
use Serixa\AuthUi\Components\PasswordStrength;
use Serixa\AuthUi\Layout\AuthCard;
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\RegisterCard;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$layout = AuthLayout::make()->card(AuthCard::make(
    RegisterCard::make()
        ->action('#')
        ->brand(BrandLogo::make('SERIXA'))
        ->title('Create account'),
    PasswordStrength::make('ExamplePass12'),
    PasswordRequirements::make()
        ->requirement('At least 12 characters', true)
        ->requirement('Contains a number', true)
        ->requirement('Contains a symbol', false),
));

$document = Document::make()->title('Register · SERIXA Auth UI')->body($layout);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
