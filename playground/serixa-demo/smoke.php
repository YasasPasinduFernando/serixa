<?php

declare(strict_types=1);

$pages = ['login', 'dashboard', 'crud', 'settings', 'profile'];
$needles = [
    'login' => ['Sign in', 'Missing widget: Input'],
    'dashboard' => ['Dashboard', 'Missing widget: Chart'],
    'crud' => ['Missing widget: DataTable'],
    'settings' => ['Missing widget: Switch'],
    'profile' => ['Missing widget: Avatar'],
];

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
