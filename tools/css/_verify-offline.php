<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);

$checks = [
    'website' => static function () use ($root): void {
        require $root . '/website/vendor/autoload.php';
        $html = (new Serixa\Rendering\Renderer())->render(
            App\DocumentFactory::make('t', Serixa\Component\Text::make('x')),
        );
        echo 'website '
            . (str_contains($html, 'cdn.tailwindcss') ? 'CDN_BAD' : 'CDN_OK') . ' '
            . (str_contains($html, '/assets/serixa.css') ? 'CSS_OK' : 'CSS_BAD') . ' '
            . (str_contains($html, 'serixa.js') ? 'JS_OK' : 'JS_BAD') . PHP_EOL;
    },
    'blog' => static function () use ($root): void {
        require $root . '/starters/blog/vendor/autoload.php';
        $html = (new Serixa\Rendering\Renderer())->render(
            App\DocumentFactory::make('t', Serixa\Component\Text::make('x')),
        );
        echo 'blog '
            . (str_contains($html, 'cdn.tailwindcss') ? 'CDN_BAD' : 'CDN_OK') . ' '
            . (str_contains($html, '/assets/serixa.css') ? 'CSS_OK' : 'CSS_BAD') . PHP_EOL;
    },
    'admin' => static function () use ($root): void {
        require $root . '/starters/admin/vendor/autoload.php';
        $html = (new Serixa\Rendering\Renderer())->render(
            App\DocumentFactory::make('t', Serixa\Component\Text::make('x')),
        );
        echo 'admin '
            . (str_contains($html, 'cdn.tailwindcss') ? 'CDN_BAD' : 'CDN_OK') . ' '
            . (str_contains($html, '/assets/serixa.css') ? 'CSS_OK' : 'CSS_BAD') . PHP_EOL;
    },
    'playground' => static function () use ($root): void {
        require $root . '/playground/serixa-demo/vendor/autoload.php';
        $html = (new Serixa\Rendering\Renderer())->render(
            App\DocumentFactory::make('t', Serixa\Component\Text::make('x')),
        );
        echo 'playground '
            . (str_contains($html, 'cdn.tailwindcss') ? 'CDN_BAD' : 'CDN_OK') . ' '
            . (str_contains($html, '/assets/serixa.css') ? 'CSS_OK' : 'CSS_BAD') . PHP_EOL;
    },
];

foreach ($checks as $name => $fn) {
    try {
        $fn();
    } catch (Throwable $e) {
        echo $name . ' ERROR ' . $e->getMessage() . PHP_EOL;
    }
}
