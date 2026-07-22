<?php

declare(strict_types=1);

namespace Serixa\Excel;

final class Spreadsheet
{
    /** @var list<string> */
    private array $headers = [];

    /** @var list<list<scalar|null>> */
    private array $rows = [];

    public static function make(): self
    {
        return new self();
    }

    /**
     * @param list<string> $headers
     */
    public function headers(array $headers): self
    {
        $this->headers = array_values($headers);

        return $this;
    }

    /**
     * @param list<scalar|null> $row
     */
    public function addRow(array $row): self
    {
        $this->rows[] = array_values($row);

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return list<list<scalar|null>>
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    public function toCsv(string $delimiter = ',', string $enclosure = '"'): string
    {
        $fh = fopen('php://temp', 'r+');
        if ($fh === false) {
            return '';
        }
        if ($this->headers !== []) {
            fputcsv($fh, $this->headers, $delimiter, $enclosure);
        }
        foreach ($this->rows as $row) {
            fputcsv($fh, array_map(static fn ($v) => $v === null ? '' : (string) $v, $row), $delimiter, $enclosure);
        }
        rewind($fh);
        $csv = stream_get_contents($fh) ?: '';
        fclose($fh);

        return $csv;
    }
}
