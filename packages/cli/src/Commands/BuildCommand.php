<?php

declare(strict_types=1);

namespace Serixa\Cli\Commands;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Cli\Scaffold\WorkingDirectory;
use Serixa\Runtime\ClientRuntime;
use Serixa\Styling\StyleSheet;

/**
 * serixa build — compile theme CSS, copy runtime, optimize, write manifest.
 */
final class BuildCommand extends Command
{
    public function __construct(
        private readonly string $frameworkRoot,
    ) {
    }

    public function name(): string
    {
        return 'build';
    }

    public function description(): string
    {
        return 'Compile theme CSS, copy runtime JS, optimize assets, and write manifests';
    }

    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $cwd = WorkingDirectory::get();
        $buildDir = $cwd . DIRECTORY_SEPARATOR . 'build';
        $started = microtime(true);

        if (! is_dir($buildDir) && ! mkdir($buildDir, 0777, true) && ! is_dir($buildDir)) {
            $io->error(sprintf('Unable to create %s', $buildDir));

            return 1;
        }

        $report = [
            'version' => \Serixa\Cli\Application::VERSION,
            'framework_root' => $this->frameworkRoot,
            'cwd' => $cwd,
            'built_at' => gmdate('c'),
            'steps' => [],
        ];

        // 1) Compile CSS (ThemeCompiler → optimize → foundation → app) → public/assets/serixa.css
        try {
            $css = StyleSheet::build($cwd);
            $report['steps']['css'] = [
                'path' => 'public/assets/serixa.css',
                'bytes' => $css['bytes'],
                'sources' => $css['sources'],
                'rules' => $css['rules'] ?? null,
                'ms' => $css['ms'],
            ];
        } catch (\Throwable $e) {
            $io->error('CSS build failed: ' . $e->getMessage());

            return 1;
        }

        // 2) Copy progressive-enhancement runtime
        $runtimeSrc = ClientRuntime::scriptPath();
        $runtimeDestDir = $cwd . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'runtime';
        $runtimeDest = $runtimeDestDir . DIRECTORY_SEPARATOR . 'serixa.js';

        if (! is_dir($runtimeDestDir) && ! mkdir($runtimeDestDir, 0777, true) && ! is_dir($runtimeDestDir)) {
            $io->error(sprintf('Unable to create %s', $runtimeDestDir));

            return 1;
        }

        $runtimeBytes = 0;
        if (is_file($runtimeSrc) && copy($runtimeSrc, $runtimeDest)) {
            $runtimeBytes = (int) filesize($runtimeDest);
        }
        $report['steps']['runtime'] = [
            'path' => 'public/assets/runtime/serixa.js',
            'bytes' => $runtimeBytes,
        ];

        // 3) Theme token snapshot (from compiler manifest when available)
        $tokensPath = $buildDir . DIRECTORY_SEPARATOR . 'tokens.json';
        $tokenPayload = [
            'tokens' => $css['manifest']->toArray()['tokens'] ?? [],
            'generated_at' => gmdate('c'),
            'engine' => 'Serixa\\Styling\\ThemeCompiler',
        ];
        file_put_contents($tokensPath, json_encode($tokenPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $report['steps']['tokens'] = ['path' => 'build/tokens.json', 'bytes' => filesize($tokensPath)];

        // 4) Asset manifest
        $manifest = [
            'version' => \Serixa\Cli\Application::VERSION,
            'framework_root' => $this->frameworkRoot,
            'built_at' => gmdate('c'),
            'css' => StyleSheet::PUBLIC_URL,
            'runtime' => '/assets/runtime/serixa.js',
            'tokens' => 'build/tokens.json',
            'theme_manifest' => 'build/theme-manifest.json',
            'assets' => [
                'serixa.css' => StyleSheet::PUBLIC_URL,
                'serixa.js' => '/assets/runtime/serixa.js',
            ],
            'sources' => $css['sources'],
            'sizes' => [
                'serixa.css' => $css['bytes'],
                'serixa.js' => $runtimeBytes,
            ],
            'rules' => $css['rules'] ?? null,
        ];

        $manifestBuild = $buildDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifestPublicDir = $cwd . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets';
        $manifestPublic = $manifestPublicDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $json = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($manifestBuild, $json);
        file_put_contents($manifestPublic, $json);
        $report['steps']['manifest'] = [
            'path' => 'build/manifest.json',
            'public' => 'public/assets/manifest.json',
            'bytes' => strlen((string) $json),
        ];

        // 5) Docs cache (best-effort)
        (new DocsCommand($this->frameworkRoot))->handle([], [], new InputOutput(true));

        $report['elapsed_ms'] = round((microtime(true) - $started) * 1000, 2);
        file_put_contents(
            $buildDir . DIRECTORY_SEPARATOR . 'report.json',
            json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );

        $io->success('Build complete.');
        $io->info(sprintf(
            '  CSS      public/assets/serixa.css (%s bytes, %s rules, %s ms)',
            number_format($css['bytes']),
            number_format((int) ($css['rules'] ?? 0)),
            $css['ms'],
        ));
        foreach ($css['sources'] as $source) {
            $io->info('           + ' . $source);
        }
        $io->info(sprintf('  Runtime  public/assets/runtime/serixa.js (%s bytes)', number_format($runtimeBytes)));
        $io->info('  Manifest build/manifest.json');
        $io->info('  Theme    build/theme-manifest.json');
        $io->info(sprintf('  Elapsed  %s ms', $report['elapsed_ms']));

        return 0;
    }
}
