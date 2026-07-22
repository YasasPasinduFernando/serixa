<?php

declare(strict_types=1);

namespace Serixa\Menu\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Menu\Menu;
use Serixa\Menu\MenuItem;

final class MenuTest extends TestCase
{
    public function testNestedMenu(): void
    {
        $menu = Menu::make('main')
            ->add(MenuItem::make('Home', '/'))
            ->add(
                MenuItem::make('Docs', '/docs')
                    ->add(MenuItem::make('CMS', '/docs/cms')),
            );

        self::assertCount(2, $menu->items());
        self::assertTrue($menu->items()[1]->hasChildren());
        self::assertSame('CMS', $menu->toArray()[1]['children'][0]['label']);
    }
}
