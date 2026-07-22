#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Verify package skeletons exist and composer.json is readable.
 */
$root = dirname(__DIR__, 2);
$packages = [
    'icons', 'charts', 'calendar', 'admin', 'auth-ui', 'devtools', 'cli', 'forms',
    'rbac', 'permissions', 'organization', 'teams', 'activity-log', 'audit',
    'cart', 'checkout', 'invoice', 'tax', 'subscriptions',
];
$failed = 0;

foreach ($packages as $name) {
    $composer = $root . '/packages/' . $name . '/composer.json';
    if (! is_file($composer)) {
        fwrite(STDERR, "Missing composer.json for serixa/{$name}\n");
        $failed++;
        continue;
    }
    $json = json_decode((string) file_get_contents($composer), true);
    if (! is_array($json) || ($json['name'] ?? '') !== 'serixa/' . $name) {
        fwrite(STDERR, "Invalid package name in packages/{$name}\n");
        $failed++;
        continue;
    }
    fwrite(STDOUT, "OK serixa/{$name}\n");
}

exit($failed > 0 ? 1 : 0);
