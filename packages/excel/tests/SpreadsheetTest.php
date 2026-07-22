<?php

declare(strict_types=1);

namespace Serixa\Excel\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Excel\Spreadsheet;

final class SpreadsheetTest extends TestCase
{
    public function testCsvExport(): void
    {
        $csv = Spreadsheet::make()
            ->headers(['Name', 'Qty'])
            ->addRow(['Widget', 2])
            ->toCsv();

        self::assertStringContainsString('Name,Qty', $csv);
        self::assertStringContainsString('Widget,2', $csv);
    }
}
