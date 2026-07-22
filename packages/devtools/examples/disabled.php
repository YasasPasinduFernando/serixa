<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Component\Text;
use Serixa\DevTools\DevTools;
use Serixa\Rendering\Renderer;

DevTools::disable();

$document = devtools_example_document('DevTools disabled', [
    Text::make('DevTools disabled')->as('h1')->size('xl')->weight('bold'),
    Text::make('injectHtml returns the HTML unchanged when DevTools is disabled.'),
], withDevToolsAssets: false);

$html = (new Renderer())->render($document);
$injected = DevTools::injectHtml($html);

if ($injected === $html && ! str_contains($html, 'serixa-devtools')) {
    echo "injectHtml is identity when disabled\n";
} else {
    echo "unexpected: DevTools leaked into disabled output\n";
}

echo $injected, PHP_EOL;
