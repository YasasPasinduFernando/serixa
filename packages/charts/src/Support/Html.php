<?php

declare(strict_types=1);

namespace Serixa\Charts\Support;

/**
 * Small SSR-only markup helpers shared across chart widgets.
 *
 * @internal
 */
final class Html
{
    private function __construct()
    {
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Join non-empty string fragments.
     */
    public static function join(string ...$fragments): string
    {
        $parts = array_filter($fragments, static fn (string $fragment): bool => $fragment !== '');

        return implode('', $parts);
    }

    /**
     * Render an attribute-driven HTML tag with inner HTML that is already escaped/rendered.
     */
    public static function tag(string $tag, string $attributes, string $inner = ''): string
    {
        return sprintf('<%1$s%2$s>%3$s</%1$s>', $tag, $attributes, $inner);
    }

    /**
     * Render a self-closing (void) HTML tag.
     */
    public static function voidTag(string $tag, string $attributes): string
    {
        return sprintf('<%s%s />', $tag, $attributes);
    }

    /**
     * Encode a JSON payload for use inside an HTML attribute.
     *
     * @param array<string, mixed> $payload
     */
    public static function jsonAttr(array $payload): string
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        return self::escape($json);
    }
}
