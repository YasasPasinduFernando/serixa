<?php

declare(strict_types=1);

namespace Serixa\Cli\Commands;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Rendering\Renderer;
use Serixa\Styling\StyleCompiler;
use Serixa\Styling\ThemeCompiler;
use Serixa\Theme\DefaultTheme;

/**
 * serixa benchmark — lightweight local performance sample.
 */
final class BenchmarkCommand extends Command
{
    public function name(): string
    {
        return 'benchmark';
    }

    public function description(): string
    {
        return 'Sample ThemeCompiler, StyleCompiler, and Renderer timings';
    }

    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $io->writeln('SERIXA benchmark');
        $io->writeln(str_repeat('─', 40));

        $t0 = microtime(true);
        $rules = (new ThemeCompiler())->compile();
        $themeMs = round((microtime(true) - $t0) * 1000, 3);

        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'serixa-bench-' . bin2hex(random_bytes(4));
        mkdir($tmp . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets', 0777, true);
        $t0 = microtime(true);
        $css = (new StyleCompiler())->compile($tmp);
        $cssMs = round((microtime(true) - $t0) * 1000, 3);

        $renderer = new Renderer();
        $tree = Column::make(Text::make('Bench'), Button::make('Go')->primary());
        $t0 = microtime(true);
        $iterations = 100;
        $html = '';
        for ($i = 0; $i < $iterations; ++$i) {
            $html = $renderer->render($tree);
        }
        $renderMs = round((microtime(true) - $t0) * 1000, 3);

        $t0 = microtime(true);
        $theme = new DefaultTheme();
        for ($i = 0; $i < 500; ++$i) {
            $theme->classes('button', ['variant' => 'primary']);
        }
        $mapMs = round((microtime(true) - $t0) * 1000, 3);

        $io->writeln(sprintf('  ThemeCompiler     %6.2f ms  (%d rules)', $themeMs, count($rules)));
        $io->writeln(sprintf('  StyleCompiler     %6.2f ms  (%s bytes)', $cssMs, number_format($css['bytes'])));
        $io->writeln(sprintf('  Renderer x%-4d   %6.2f ms  (avg %.3f ms, %d HTML bytes)', $iterations, $renderMs, $renderMs / $iterations, strlen($html)));
        $io->writeln(sprintf('  Theme map x500    %6.2f ms', $mapMs));
        $io->writeln(sprintf('  Peak memory       %s', $this->formatBytes(memory_get_peak_usage(true))));
        $io->writeln(str_repeat('─', 40));
        $io->success('Benchmark complete.');

        return 0;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        return round($bytes / 1024 / 1024, 2) . ' MB';
    }
}
