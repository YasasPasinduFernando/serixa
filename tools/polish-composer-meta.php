<?php

declare(strict_types=1);

$root = dirname(__DIR__);
if (! is_file($root . '/LICENSE')) {
    $root = __DIR__;
}

chdir($root);

$homepage = 'https://github.com/YasasPasinduFernando/serixa';
$support = [
    'issues' => $homepage . '/issues',
    'source' => $homepage,
    'docs' => $homepage . '/tree/main/docs',
];
$authors = [['name' => 'SERIXA Contributors', 'homepage' => $homepage]];

$starterDesc = [
    'starters/crm/composer.json' => 'SERIXA CRM starter kit — dashboard shell for customer management apps.',
    'starters/docs/composer.json' => 'SERIXA docs starter kit — documentation site scaffold.',
    'starters/ecommerce/composer.json' => 'SERIXA ecommerce starter kit — storefront shell for product catalogs.',
    'starters/portfolio/composer.json' => 'SERIXA portfolio starter kit — personal site scaffold.',
];

$targets = array_merge(
    [$root . '/composer.json'],
    glob($root . '/framework/composer.json') ?: [],
    glob($root . '/packages/*/composer.json') ?: [],
    glob($root . '/starters/*/composer.json') ?: [],
    glob($root . '/website/composer.json') ?: [],
    glob($root . '/playground/*/composer.json') ?: [],
    glob($root . '/validation/*/composer.json') ?: [],
);

$order = [
    'name', 'description', 'type', 'license', 'homepage', 'support', 'keywords', 'authors',
    'require', 'require-dev', 'autoload', 'autoload-dev', 'suggest', 'repositories',
    'scripts', 'minimum-stability', 'prefer-stable', 'private', 'config',
];

foreach ($targets as $path) {
    $json = json_decode((string) file_get_contents($path), true);
    if (! is_array($json)) {
        fwrite(STDERR, "FAIL {$path}\n");
        continue;
    }

    $rel = str_replace('\\', '/', substr($path, strlen($root) + 1));
    $json['homepage'] = $homepage;
    $json['support'] = $support;
    $json['authors'] = $authors;
    $json['license'] = $json['license'] ?? 'MIT';

    if (isset($starterDesc[$rel])) {
        $json['description'] = $starterDesc[$rel];
    }

    if (
        (str_starts_with($rel, 'starters/') || str_starts_with($rel, 'website/') || str_starts_with($rel, 'playground/'))
        && empty($json['keywords'])
    ) {
        $json['keywords'] = ['serixa', 'php', 'ui'];
    }

    $out = [];
    foreach ($order as $key) {
        if (array_key_exists($key, $json)) {
            $out[$key] = $json[$key];
        }
    }
    foreach ($json as $key => $value) {
        if (! array_key_exists($key, $out)) {
            $out[$key] = $value;
        }
    }

    file_put_contents($path, json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    echo "ok {$rel}\n";
}

$lic = (string) file_get_contents($root . '/LICENSE');
foreach ([
    'website',
    'playground/serixa-demo',
    'starters/crm',
    'starters/docs',
    'starters/ecommerce',
    'starters/portfolio',
    'validation/admin-dashboard',
    'validation/blog',
    'validation/crm',
] as $dir) {
    file_put_contents($root . '/' . $dir . '/LICENSE', $lic);
    echo "lic {$dir}\n";
}

echo "done\n";
