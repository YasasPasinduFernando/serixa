<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\Contracts\DocumentInterface;
use Serixa\DevTools\Contracts\InspectorInterface;

final class AssetTreeInspector implements InspectorInterface
{
    public function name(): string
    {
        return 'asset_tree';
    }

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array
    {
        if (! $subject instanceof DocumentInterface) {
            return ['assets' => []];
        }

        $assets = [];

        foreach ($subject->assets()->collect() as $asset) {
            $assets[] = [
                'type' => $asset['type'],
                'path' => $asset['path'],
            ];
        }

        return [
            'assets' => $assets,
            'count' => count($assets),
        ];
    }
}
