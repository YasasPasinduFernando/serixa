<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use NovaPHP\Component\Button;
use NovaPHP\Component\Card;
use NovaPHP\Component\Center;
use NovaPHP\Component\Column;
use NovaPHP\Component\Container;
use NovaPHP\Component\SizedBox;
use NovaPHP\Component\Text;
use NovaPHP\Rendering\Renderer;

$ui = Container::make(
    Center::make(
        Column::make([
            Card::make()
                ->title('SERIXA')
                ->child(
                    Text::make('Write PHP. Get semantic HTML.')
                        ->tone('muted'),
                )
                ->child(SizedBox::height(4))
                ->child(
                    Button::make('Get started')
                        ->primary()
                        ->large(),
                ),
        ])->alignCenter()->gap(4),
    ),
)->maxWidth('md');

echo (new Renderer())->render($ui), PHP_EOL;
