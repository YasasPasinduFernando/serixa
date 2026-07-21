<?php

declare(strict_types=1);

namespace App\Components;

use Serixa\Component\Link;

/**
 * Sidebar link list for CRM navigation.
 */
final class CrmNav
{
    /**
     * @return list<Link>
     */
    public static function links(string $active): array
    {
        $items = [
            'dashboard' => 'Dashboard',
            'customers' => 'Customers',
            'companies' => 'Companies',
            'invoices' => 'Invoices',
            'tasks' => 'Tasks',
            'calendar' => 'Calendar',
            'profile' => 'Profile',
            'settings' => 'Settings',
            'login' => 'Login',
        ];

        $links = [];

        foreach ($items as $key => $label) {
            $link = Link::make($label)->href('/?page=' . $key);

            if ($key === $active) {
                $link->active();
            }

            $links[] = $link;
        }

        return $links;
    }
}
