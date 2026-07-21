<?php

declare(strict_types=1);

/**
 * Project entry hint. Prefer the public front controller:
 *
 *   composer install
 *   php -S localhost:8000 -t public
 */
$public = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';

if (PHP_SAPI === 'cli') {
    fwrite(STDOUT, "SERIXA demo\n");
    fwrite(STDOUT, "Run: composer install && php -S localhost:8000 -t public\n");
    fwrite(STDOUT, "Then open http://localhost:8000/?page=login\n");
    exit(0);
}

require $public;
