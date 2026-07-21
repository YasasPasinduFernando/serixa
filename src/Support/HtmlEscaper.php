<?php

declare(strict_types=1);

namespace NovaPHP\Support;

/**
 * Escapes values for safe inclusion in HTML content and attributes.
 *
 * Single responsibility: XSS-safe string encoding. No markup generation.
 */
final class HtmlEscaper
{
    public function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
