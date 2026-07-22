<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;

final class AccessibilityCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('accessibility');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        $html = $ctx->html();
        $issues = [];

        if ($html === '') {
            return ['issues' => $issues, 'issueCount' => 0];
        }

        if (! preg_match('/<html\b[^>]*\blang\s*=/i', $html)) {
            $issues[] = [
                'type' => 'missing_lang',
                'message' => 'Document <html> element is missing a lang attribute.',
            ];
        }

        preg_match_all('/<img\b[^>]*>/i', $html, $imgMatches);

        foreach ($imgMatches[0] as $tag) {
            if (! preg_match('/\balt\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', $tag)) {
                $issues[] = [
                    'type' => 'missing_alt',
                    'message' => 'Image tag without alt attribute detected.',
                    'snippet' => mb_substr($tag, 0, 120),
                ];
            }
        }

        return [
            'issues' => $issues,
            'issueCount' => count($issues),
        ];
    }
}
