<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Excel\Spreadsheet;

$csv = Spreadsheet::make()->headers(['A'])->addRow(['1'])->toCsv();
echo $csv;
echo str_contains($csv, 'A') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit(str_contains($csv, 'A') ? 0 : 1);
