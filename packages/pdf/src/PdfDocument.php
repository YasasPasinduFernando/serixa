<?php

declare(strict_types=1);

namespace Serixa\Pdf;

/**
 * Minimal PDF builder (single-page text) without external engines.
 */
final class PdfDocument
{
    /** @var list<string> */
    private array $lines = [];

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

    public function line(string $text): self
    {
        $this->lines[] = $text;

        return $this;
    }

    /**
     * @param list<string> $lines
     */
    public function lines(array $lines): self
    {
        foreach ($lines as $line) {
            $this->line($line);
        }

        return $this;
    }

    public function toString(): string
    {
        $content = "BT /F1 12 Tf 50 750 Td\n";
        $first = true;
        foreach ($this->lines as $line) {
            $escaped = $this->escape($line);
            if (! $first) {
                $content .= "0 -16 Td\n";
            }
            $content .= "({$escaped}) Tj\n";
            $first = false;
        }
        $content .= 'ET';

        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>';
        $objects[] = '<< /Length ' . strlen($content) . " >>\nstream\n" . $content . "\nendstream";
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $i => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= 'xref' . "\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); ++$i) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . ' /Root 1 0 R /Info << /Title (' . $this->escape($this->title) . ") >> >>\n";
        $pdf .= "startxref\n{$xref}\n%%EOF\n";

        return $pdf;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
