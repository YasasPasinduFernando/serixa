<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Pdf\PdfDocument;

$pdf = PdfDocument::make()->title('Smoke')->line('Hello PDF')->toString();
echo substr($pdf, 0, 8), PHP_EOL;
echo str_starts_with($pdf, '%PDF') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit(str_starts_with($pdf, '%PDF') ? 0 : 1);
