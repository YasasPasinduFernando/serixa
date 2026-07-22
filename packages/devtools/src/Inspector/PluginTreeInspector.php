<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\DevTools\Contracts\InspectorInterface;
use Serixa\Plugin\PluginManager;
use Serixa\Plugin\RuntimePlugin;

final class PluginTreeInspector implements InspectorInterface
{
    public function name(): string
    {
        return 'plugin_tree';
    }

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array
    {
        if ($subject instanceof PluginManager) {
            return $this->fromManager($subject);
        }

        return [
            'plugins' => [],
            'runtimePlugins' => [],
            'available' => false,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function fromManager(PluginManager $manager): array
    {
        $plugins = [];
        $runtimePlugins = [];

        foreach ($manager->all() as $name => $plugin) {
            $entry = [
                'name' => $name,
                'class' => $plugin::class,
            ];

            $plugins[] = $entry;

            if ($plugin instanceof RuntimePlugin) {
                $runtimePlugins[] = $entry;
            }
        }

        return [
            'plugins' => $plugins,
            'runtimePlugins' => $runtimePlugins,
            'available' => true,
        ];
    }
}
