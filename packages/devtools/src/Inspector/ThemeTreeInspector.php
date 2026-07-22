<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\Contracts\ThemeInterface;
use Serixa\DevTools\Contracts\InspectorInterface;
use Serixa\Theme\ThemeManager;

final class ThemeTreeInspector implements InspectorInterface
{
    public function name(): string
    {
        return 'theme_tree';
    }

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array
    {
        $theme = $subject instanceof ThemeInterface ? $subject : ThemeManager::current();
        $chain = [];
        $current = $theme;

        while (true) {
            $chain[] = $current::class;

            if (! method_exists($current, 'inner')) {
                break;
            }

            $next = $current->inner();

            if (! $next instanceof ThemeInterface || $next === $current) {
                break;
            }

            $current = $next;
        }

        return [
            'active' => $theme::class,
            'chain' => $chain,
            'depth' => count($chain),
        ];
    }
}
