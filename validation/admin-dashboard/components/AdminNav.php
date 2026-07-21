<?php

declare(strict_types=1);

namespace App\Components;

use Serixa\Component\Link;
use Serixa\Contracts\WidgetInterface;

/**
 * Optional nav helper (shell embeds links directly; kept for composition).
 */
final class AdminNav
{
    /**
     * @return list<WidgetInterface>
     */
    public static function links(string $active): array
    {
        $items = [
            'dashboard' => 'Dashboard',
            'analytics' => 'Analytics',
            'users' => 'Users',
            'roles' => 'Roles',
            'crud' => 'CRUD',
            'settings' => 'Settings',
            'profile' => 'Profile',
            'notifications' => 'Notifications',
            'login' => 'Login',
        ];

        $links = [];

        foreach ($items as $page => $label) {
            $links[] = Link::make($label)
                ->href('/?page=' . $page)
                ->active($page === $active);
        }

        return $links;
    }
}
