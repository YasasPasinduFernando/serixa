<?php

declare(strict_types=1);

$pages = ['login', 'dashboard', 'crud', 'settings', 'profile', 'interactive'];
$needles = [
    'login' => [
        'Sign in',
        'type="email"',
        'SERIXA',
        'Remember me',
        'data-serixa-on-submit="login"',
        'required',
        'serixa.js',
    ],
    'dashboard' => [
        '1,284',
        'Avg latency',
        'Ada joined',
        'data-serixa-dropdown',
        'role="tablist"',
        'data-serixa-toast',
        'Live',
    ],
    'crud' => [
        'User directory',
        'type="search"',
        'Pagination',
        'Ada Lovelace',
        'data-serixa-modal',
        'role="progressbar"',
        'data-serixa-spinner',
    ],
    'settings' => [
        'Workspace name',
        'role="tablist"',
        'data-serixa-accordion',
        'Notifications',
    ],
    'profile' => [
        'AL',
        'SERIXA',
        'Profile cover',
        'role="tooltip"',
        'Pro',
    ],
    'interactive' => [
        'Interactive components',
        'data-serixa-modal',
        'data-serixa-drawer',
        'role="tablist"',
        'data-serixa-accordion',
    ],
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
