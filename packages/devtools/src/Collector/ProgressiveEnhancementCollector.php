<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;

final class ProgressiveEnhancementCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('progressive_enhancement');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        $html = $ctx->html();

        if ($html === '') {
            return [
                'hasSerixaJs' => false,
                'dataSerixaAttributes' => [],
                'moduleHints' => [],
            ];
        }

        preg_match_all('/\bdata-serixa-[a-z0-9-]+/i', $html, $attrMatches);
        $attributes = array_values(array_unique($attrMatches[0]));

        preg_match_all('/\bserixa\.(?:boot|registerModule|mount)\b/', $html, $moduleMatches);
        $moduleHints = array_values(array_unique($moduleMatches[0]));

        return [
            'hasSerixaJs' => str_contains($html, 'serixa.js') || str_contains($html, 'Serixa'),
            'dataSerixaAttributes' => $attributes,
            'moduleHints' => $moduleHints,
        ];
    }
}
