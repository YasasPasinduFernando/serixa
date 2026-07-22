<?php

declare(strict_types=1);

namespace Serixa\DevTools\Runtime;

/**
 * Injects DevTools overlay markup before the closing body tag.
 */
final class Injector
{
    public function injectHtml(string $html, string $overlayHtml): string
    {
        if ($overlayHtml === '') {
            return $html;
        }

        if (preg_match('/<\/body>/i', $html, $matches, PREG_OFFSET_CAPTURE)) {
            $position = $matches[0][1];

            return substr($html, 0, $position) . $overlayHtml . substr($html, $position);
        }

        return $html . $overlayHtml;
    }
}
