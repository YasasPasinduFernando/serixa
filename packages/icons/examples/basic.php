<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$renderer = new Renderer();

echo $renderer->render(Icon::make('home'));
echo "\n";
echo $renderer->render(Icon::make('search')->size('lg')->color('primary'));
echo "\n";
echo $renderer->render(Icon::make('mail')->ariaLabel('Email'));
echo "\n";
echo $renderer->render(Icon::make('heart')->decorative());
echo "\n";
