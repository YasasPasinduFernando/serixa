<?php

declare(strict_types=1);

namespace App\Components;

use Serixa\Component\Link;
use Serixa\Component\Row;
use Serixa\Contracts\WidgetInterface;

final class SiteNav
{
    public static function make(string $active = ''): WidgetInterface
    {
        $items = [
            'home' => 'Home',
            'articles' => 'Articles',
            'categories' => 'Categories',
            'about' => 'About',
            'contact' => 'Contact',
            'login' => 'Login',
            'profile' => 'Profile',
        ];

        $links = [];
        foreach ($items as $page => $label) {
            $links[] = Link::make($label)
                ->href('/?page=' . $page)
                ->active($active === $page);
        }

        return Row::make($links)->gap(3)->alignCenter();
    }
}
