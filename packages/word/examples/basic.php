<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Word\WordDocument;

$rtf = WordDocument::make()->paragraph('Hi')->toRtf();
echo str_starts_with($rtf, '{\\rtf1') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit(str_starts_with($rtf, '{\\rtf1') ? 0 : 1);
