<?php

declare(strict_types=1);

namespace Serixa\Word;

/**
 * Simple document builder exporting RTF (widely Word-compatible).
 */
final class WordDocument
{
    /** @var list<string> */
    private array $paragraphs = [];

    private string $title = 'Document';

    public static function make(): self
    {
        return new self();
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function paragraph(string $text): self
    {
        $this->paragraphs[] = $text;

        return $this;
    }

    public function toRtf(): string
    {
        $body = '';
        foreach ($this->paragraphs as $paragraph) {
            $body .= '\\pard ' . $this->escape($paragraph) . '\\par' . "\n";
        }

        return "{\\rtf1\\ansi\\deff0\n"
            . '{\\info{\\title ' . $this->escape($this->title) . "}}\n"
            . $body
            . "}\n";
    }

    private function escape(string $text): string
    {
        return str_replace(
            ['\\', '{', '}', "\n"],
            ['\\\\', '\\{', '\\}', '\\par '],
            $text,
        );
    }
}
