<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Component\Text;
use Serixa\DevTools\DevTools;
use Serixa\Rendering\Renderer;

DevTools::enable();

$document = devtools_example_document('DevTools demo', [
    Text::make('DevTools demo')->as('h1')->size('xl')->weight('bold'),
    Text::make('Press Ctrl+Shift+D to toggle the toolbar. Hold Alt to inspect elements.'),
]);

$html = (new Renderer())->render($document);
echo DevTools::injectHtml($html), PHP_EOL;
