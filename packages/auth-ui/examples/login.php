<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Styling\StyleSheet;

use Serixa\AuthUi\Components\AuthDivider;
use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Components\SocialLoginButtons;
use Serixa\AuthUi\Layout\AuthCard;
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\LoginCard;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$card = LoginCard::make()
    ->action('#')
    ->brand(BrandLogo::make('SERIXA'))
    ->title('Sign in')
    ->subtitle('UI only — wire action() to your auth endpoint.');

$layout = AuthLayout::make()
    ->brand(BrandLogo::make('SERIXA')->href('/'))
    ->card(AuthCard::make(
        $card,
        AuthDivider::make('or'),
        SocialLoginButtons::make()
            ->provider('GitHub', '#', 'code-bracket')
            ->provider('Google', '#', 'at-symbol'),
    ));

$document = Document::make()->title('Login · SERIXA Auth UI')->body($layout);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
