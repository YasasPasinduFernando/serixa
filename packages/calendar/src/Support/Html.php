<?php

declare(strict_types=1);

namespace Serixa\Calendar\Support;

/**
 * SSR-only markup helpers for calendar widgets.
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

    public static function tag(string $tag, string $attributes, string $inner = ''): string
    {
        return sprintf('<%1$s%2$s>%3$s</%1$s>', $tag, $attributes, $inner);
    }

    public static function voidTag(string $tag, string $attributes): string
    {
        return sprintf('<%s%s />', $tag, $attributes);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function jsonAttr(array $payload): string
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        return self::escape($json);
    }

    /**
     * @param list<string> $classes
     */
    public static function classAttr(array $classes): string
    {
        $joined = trim(implode(' ', array_filter($classes, static fn (string $c): bool => $c !== '')));

        return $joined === '' ? '' : ' class="' . self::escape($joined) . '"';
    }
}
