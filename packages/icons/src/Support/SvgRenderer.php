<?php

declare(strict_types=1);

namespace Serixa\Icons\Support;

/**
 * Builds inline SVG markup from path definitions.
 *
 * @internal
 */
final class SvgRenderer
{
    /**
     * @param list<array{d: string, fill_rule?: string}> $paths
     */
    public static function render(
        string $viewBox,
        array $paths,
        string $attributeString,
        bool $filled,
        float $strokeWidth,
        ?string $title,
    ): string {
        $inner = '';

        if ($title !== null && $title !== '') {
            $inner .= '<title>' . htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</title>';
        }

        foreach ($paths as $path) {
            $d = htmlspecialchars($path['d'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $fillRule = isset($path['fill_rule'])
                ? ' fill-rule="' . htmlspecialchars($path['fill_rule'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"'
                : '';

            if ($filled) {
                $inner .= sprintf('<path d="%s"%s fill="currentColor"/>', $d, $fillRule);
            } else {
                $inner .= sprintf(
                    '<path d="%s"%s fill="none" stroke="currentColor" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round"/>',
                    $d,
                    $fillRule,
                    rtrim(rtrim(sprintf('%.2F', $strokeWidth), '0'), '.'),
                );
            }
        }

        return sprintf(
            '<svg%s xmlns="http://www.w3.org/2000/svg" viewBox="%s" fill="none">%s</svg>',
            $attributeString,
            htmlspecialchars($viewBox, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $inner,
        );
    }
}
