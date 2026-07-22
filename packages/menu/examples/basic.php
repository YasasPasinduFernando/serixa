<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Menu\Menu;
use Serixa\Menu\MenuItem;

$menu = Menu::make('main')
    ->add(MenuItem::make('Home', '/'))
    ->add(MenuItem::make('Blog', '/blog')->add(MenuItem::make('Archive', '/blog/archive')));

echo 'items: ', count($menu->items()), PHP_EOL;
echo json_encode($menu->toArray()), PHP_EOL;

if (count($menu->items()) === 2) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
