#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Verify starter skeletons exist.
 */
$root = dirname(__DIR__, 2);
$starters = ['blog', 'admin', 'crm', 'ecommerce', 'portfolio', 'docs'];
$failed = 0;

foreach ($starters as $name) {
    $base = $root . '/starters/' . $name;
    foreach (['composer.json', 'README.md', 'public/index.php', 'config/app.php'] as $rel) {
        if (! is_file($base . '/' . $rel)) {
            fwrite(STDERR, "Missing starters/{$name}/{$rel}\n");
            $failed++;
        }
    }
    foreach (['app', 'components', 'layouts', 'pages', 'public', 'config'] as $dir) {
        if (! is_dir($base . '/' . $dir)) {
            fwrite(STDERR, "Missing starters/{$name}/{$dir}\n");
            $failed++;
        }
    }
    if ($failed === 0) {
        fwrite(STDOUT, "OK starter {$name}\n");
    }
}

exit($failed > 0 ? 1 : 0);
