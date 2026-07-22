<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;

final class AssetLoadingCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('asset_loading');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        $document = $ctx->document();

        if ($document === null) {
            return ['scripts' => [], 'styles' => [], 'other' => []];
        }

        $assets = $document->assets()->collect();

        $scripts = [];
        $styles = [];
        $other = [];

        foreach ($assets as $asset) {
            $entry = [
                'path' => $asset['path'],
                'options' => $asset['options'],
            ];

            match ($asset['type']) {
                'js' => $scripts[] = $entry,
                'css' => $styles[] = $entry,
                default => $other[] = array_merge(['type' => $asset['type']], $entry),
            };
        }

        return [
            'scripts' => $scripts,
            'styles' => $styles,
            'other' => $other,
            'bundleCount' => count($document->assets()->bundles()),
        ];
    }
}
