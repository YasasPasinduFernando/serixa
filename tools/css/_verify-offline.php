<?php

declare(strict_types=1);

$checks = [
    'website' => static function (): string {
        require 'D:/yasas/PersonalProjects/Serixa/website/vendor/autoload.php';
        App\SiteContext::boot('home', 'light');
        $h = (new Serixa\Rendering\Renderer())->render(App\Web::document('home'));

        return status($h);
    },
];

function status(string $h): string
{
    $cdn = str_contains($h, 'cdn.tailwindcss') ? 'CDN_BAD' : 'CDN_OK';
    $css = str_contains($h, '/assets/app.css') ? 'CSS_OK' : 'CSS_BAD';
    $js = str_contains($h, 'serixa.js') ? 'JS_OK' : 'JS_BAD';

    return "{$cdn} {$css} {$js}";
}

echo 'website ' . $checks['website']() . PHP_EOL;

// Blog in separate process to avoid App\ autoload clash
passthru('php -r ' . escapeshellarg(
    "require 'D:/yasas/PersonalProjects/Serixa/starters/blog/vendor/autoload.php'; "
    . "Serixa\\Theme\\ThemeManager::reset(); Serixa\\Icons\\Registry\\IconRegistry::reset(); "
    . "\$h=(new Serixa\\Rendering\\Renderer())->render(App\\Web::document('dashboard')); "
    . "echo 'blog '.(str_contains(\$h,'cdn.tailwindcss')?'CDN_BAD':'CDN_OK').' '.(str_contains(\$h,'/assets/app.css')?'CSS_OK':'CSS_BAD').PHP_EOL;"
));

passthru('php -r ' . escapeshellarg(
    "require 'D:/yasas/PersonalProjects/Serixa/starters/admin/vendor/autoload.php'; "
    . "Serixa\\Theme\\ThemeManager::reset(); Serixa\\Icons\\Registry\\IconRegistry::reset(); "
    . "\$h=(new Serixa\\Rendering\\Renderer())->render(App\\Web::document('dashboard')); "
    . "echo 'admin '.(str_contains(\$h,'cdn.tailwindcss')?'CDN_BAD':'CDN_OK').' '.(str_contains(\$h,'/assets/app.css')?'CSS_OK':'CSS_BAD').PHP_EOL;"
));

passthru('php -r ' . escapeshellarg(
    "require 'D:/yasas/PersonalProjects/Serixa/playground/serixa-demo/vendor/autoload.php'; "
    . "\$h=(new Serixa\\Rendering\\Renderer())->render(SerixaDemo\\DocumentFactory::page('Demo', Serixa\\Component\\Text::make('Hi'))); "
    . "echo 'playground '.(str_contains(\$h,'cdn.tailwindcss')?'CDN_BAD':'CDN_OK').' '.(str_contains(\$h,'/assets/app.css')?'CSS_OK':'CSS_BAD').PHP_EOL;"
));
