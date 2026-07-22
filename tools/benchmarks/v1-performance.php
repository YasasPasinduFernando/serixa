<?php

declare(strict_types=1);

/**
 * v1.0 stabilization benchmarks (offline).
 *
 *     php tools/benchmarks/v1-performance.php
 */

require dirname(__DIR__, 2) . '/framework/vendor/autoload.php';

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Rendering\Renderer;
use Serixa\Styling\StyleCompiler;
use Serixa\Styling\ThemeCompiler;
use Serixa\Theme\DefaultTheme;

$out = [];

// Theme compiler
$t0 = microtime(true);
$rules = (new ThemeCompiler())->compile();
$themeMs = round((microtime(true) - $t0) * 1000, 3);
$out['theme_compiler'] = [
    'ms' => $themeMs,
    'rules' => count($rules),
];

// Style compiler (temp project)
$tmp = sys_get_temp_dir() . '/serixa-bench-' . uniqid('', true);
mkdir($tmp . '/public/assets', 0777, true);
mkdir($tmp . '/resources/css', 0777, true);
file_put_contents($tmp . '/resources/css/app.css', '/* bench */');
$t0 = microtime(true);
$css = (new StyleCompiler())->compile($tmp);
$cssMs = round((microtime(true) - $t0) * 1000, 3);
$out['style_compiler'] = [
    'ms' => $cssMs,
    'bytes' => $css['bytes'],
    'rules' => $css['rules'],
    'sources' => $css['sources'],
];

// Renderer — small tree
$renderer = new Renderer();
$page = Column::make([
    Card::make(Text::make('Hello')->as('h1'), Button::make('Save')->primary()),
    Button::make('Cancel')->secondary(),
    Button::make('Delete')->danger()->disabled(),
])->gap(4);
$t0 = microtime(true);
$html = '';
for ($i = 0; $i < 200; $i++) {
    $html = $renderer->render($page);
}
$renderMs = round((microtime(true) - $t0) * 1000, 3);
$out['renderer'] = [
    'iterations' => 200,
    'total_ms' => $renderMs,
    'avg_ms' => round($renderMs / 200, 4),
    'html_bytes' => strlen($html),
    'peak_memory_bytes' => memory_get_peak_usage(true),
];

// DefaultTheme class resolution
$theme = new DefaultTheme();
$t0 = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    $theme->classes('button', ['variant' => 'primary', 'size' => 'md']);
}
$mapMs = round((microtime(true) - $t0) * 1000, 3);
$out['theme_map'] = [
    'iterations' => 1000,
    'total_ms' => $mapMs,
    'avg_ms' => round($mapMs / 1000, 4),
];

$out['php'] = PHP_VERSION;
$out['measured_at'] = gmdate('c');

$json = json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$dir = dirname(__DIR__, 2) . '/docs/generated';
if (! is_dir($dir)) {
    mkdir($dir, 0777, true);
}
file_put_contents($dir . '/performance-v1.json', $json);
echo $json, PHP_EOL;

// cleanup
foreach ([
    $tmp . '/resources/css/app.css',
    $tmp . '/public/assets/serixa.css',
    $tmp . '/public/assets/app.css',
    $tmp . '/public/assets/theme-manifest.json',
    $tmp . '/build/serixa.css',
    $tmp . '/build/theme-manifest.json',
] as $f) {
    if (is_file($f)) {
        unlink($f);
    }
}
@rmdir($tmp . '/resources/css');
@rmdir($tmp . '/resources');
@rmdir($tmp . '/public/assets');
@rmdir($tmp . '/public');
@rmdir($tmp . '/build');
@rmdir($tmp);
