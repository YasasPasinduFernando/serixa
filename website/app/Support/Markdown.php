<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Lightweight Markdown → HTML converter for first-party documentation.
 * Trusted content only (monorepo /docs and ROADMAP.md).
 */
final class Markdown
{
    private function __construct()
    {
    }

    public static function fileToHtml(string $path): string
    {
        if (! is_file($path)) {
            return '<p class="doc-missing">Documentation file not found.</p>';
        }

        return self::toHtml((string) file_get_contents($path));
    }

    public static function toHtml(string $markdown): string
    {
        $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);
        $parts = preg_split('/\n```/', $markdown) ?: [];
        $html = '';

        foreach ($parts as $i => $part) {
            if ($i % 2 === 1) {
                $nl = strpos($part, "\n");
                $code = $nl === false ? $part : substr($part, $nl + 1);
                $html .= '<pre class="doc-pre"><code>'
                    . htmlspecialchars(rtrim($code), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
                    . '</code></pre>';
                continue;
            }

            $html .= self::blocks($part);
        }

        return $html;
    }

    private static function blocks(string $text): string
    {
        $lines = explode("\n", $text);
        $out = '';
        /** @var list<string> $paragraph */
        $paragraph = [];
        $listType = null;
        /** @var list<string> $listItems */
        $listItems = [];
        /** @var list<string> $tableRows */
        $tableRows = [];

        foreach ($lines as $line) {
            $trimmed = rtrim($line);

            if ($trimmed === '') {
                $out .= self::flushParagraph($paragraph);
                $paragraph = [];
                [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
                $out .= self::flushTable($tableRows);
                $tableRows = [];
                continue;
            }

            if (preg_match('/^\|(.+)\|$/', $trimmed) === 1) {
                $out .= self::flushParagraph($paragraph);
                $paragraph = [];
                [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
                $tableRows[] = $trimmed;
                continue;
            }

            $out .= self::flushTable($tableRows);
            $tableRows = [];

            if (preg_match('/^(#{1,6})\s+(.+)$/', $trimmed, $m) === 1) {
                $out .= self::flushParagraph($paragraph);
                $paragraph = [];
                [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
                $level = strlen($m[1]);
                $out .= sprintf('<h%d>%s</h%d>', $level, self::inline($m[2]), $level);
                continue;
            }

            if (preg_match('/^---+$/', $trimmed) === 1) {
                $out .= self::flushParagraph($paragraph);
                $paragraph = [];
                [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
                $out .= '<hr>';
                continue;
            }

            if (preg_match('/^>\s?(.*)$/', $trimmed, $m) === 1) {
                $out .= self::flushParagraph($paragraph);
                $paragraph = [];
                [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
                $out .= '<blockquote><p>' . self::inline($m[1]) . '</p></blockquote>';
                continue;
            }

            if (preg_match('/^[-*]\s+(.+)$/', $trimmed, $m) === 1) {
                $out .= self::flushParagraph($paragraph);
                $paragraph = [];
                if ($listType !== null && $listType !== 'ul') {
                    [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
                }
                $listType = 'ul';
                $listItems[] = $m[1];
                continue;
            }

            if (preg_match('/^\d+\.\s+(.+)$/', $trimmed, $m) === 1) {
                $out .= self::flushParagraph($paragraph);
                $paragraph = [];
                if ($listType !== null && $listType !== 'ol') {
                    [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
                }
                $listType = 'ol';
                $listItems[] = $m[1];
                continue;
            }

            [$out, $listType, $listItems] = self::flushList($out, $listType, $listItems);
            $paragraph[] = $trimmed;
        }

        $out .= self::flushParagraph($paragraph);
        [$out] = self::flushList($out, $listType, $listItems);
        $out .= self::flushTable($tableRows);

        return $out;
    }

    /**
     * @param list<string> $paragraph
     */
    private static function flushParagraph(array $paragraph): string
    {
        if ($paragraph === []) {
            return '';
        }

        return '<p>' . self::inline(implode(' ', $paragraph)) . '</p>';
    }

    /**
     * @param list<string> $listItems
     * @return array{0: string, 1: string|null, 2: list<string>}
     */
    private static function flushList(string $out, ?string $listType, array $listItems): array
    {
        if ($listType === null || $listItems === []) {
            return [$out, null, []];
        }

        $out .= '<' . $listType . '>';
        foreach ($listItems as $item) {
            $out .= '<li>' . self::inline($item) . '</li>';
        }
        $out .= '</' . $listType . '>';

        return [$out, null, []];
    }

    /**
     * @param list<string> $rows
     */
    private static function flushTable(array $rows): string
    {
        if ($rows === []) {
            return '';
        }

        return self::renderTable($rows);
    }

    /**
     * @param list<string> $rows
     */
    private static function renderTable(array $rows): string
    {
        $html = '<div class="doc-table-wrap"><table class="doc-table">';
        $headerDone = false;

        foreach ($rows as $row) {
            if (preg_match('/^\|\s*:?-+:?\s*(\|\s*:?-+:?\s*)+\|?$/', trim($row)) === 1) {
                continue;
            }

            $cells = array_map('trim', explode('|', trim($row, '|')));
            $tag = ! $headerDone ? 'th' : 'td';
            $html .= '<tr>';
            foreach ($cells as $cell) {
                $html .= sprintf('<%1$s>%2$s</%1$s>', $tag, self::inline($cell));
            }
            $html .= '</tr>';
            $headerDone = true;
        }

        return $html . '</table></div>';
    }

    private static function inline(string $text): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text) ?? $text;
        $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text) ?? $text;
        $text = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $text) ?? $text;
        $text = preg_replace(
            '/\[([^\]]+)\]\(([^)]+)\)/',
            '<a href="$2">$1</a>',
            $text,
        ) ?? $text;

        return $text;
    }
}
