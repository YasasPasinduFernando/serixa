<?php

declare(strict_types=1);

$pages = ['login', 'dashboard', 'crud', 'settings', 'profile'];
$needles = [
    'login' => ['Sign in', 'type="email"', 'SERIXA', 'Remember me'],
    'dashboard' => ['StatCard', '1,284', 'Recent activity', 'Ada joined'],
    'crud' => ['User directory', 'type="search"', 'Pagination', 'Ada Lovelace'],
    'settings' => ['Workspace name', 'role="switch"', 'Email digests'],
    'profile' => ['AL', 'Biography', 'ada@example.com'],
];

// Dashboard uses StatCard classes indirectly — match visible content instead of class name.
$needles['dashboard'] = ['1,284', 'Recent activity', 'Ada joined', 'Missing widget: Chart'];

foreach ($pages as $page) {
    $_GET = ['page' => $page];
    ob_start();
    require __DIR__ . '/public/index.php';
    $html = ob_get_clean();

    $ok = true;
    foreach ($needles[$page] as $needle) {
        if (! str_contains($html, $needle)) {
            $ok = false;
            fwrite(STDERR, "FAIL {$page}: missing \"{$needle}\"\n");
        }
    }

    if ($ok) {
        fwrite(STDOUT, "OK {$page} (" . strlen($html) . " bytes)\n");
    } else {
        exit(1);
    }
}

fwrite(STDOUT, "All pages rendered.\n");
