<?php

declare(strict_types=1);

namespace Serixa\Cli\Commands;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;
use Serixa\Document\DocumentRenderer;
use Serixa\Pipeline\RenderPipeline;
use Serixa\Rendering\Renderer;
use Serixa\Styling\ThemeCompiler;
use Serixa\Support\ClassMerger;
use Serixa\Theme\DefaultTheme;

/**
 * serixa profile — detailed timing breakdown for core render paths.
 */
final class ProfileCommand extends Command
{
    public function name(): string
    {
        return 'profile';
    }

    public function description(): string
    {
        return 'Profile ThemeCompiler, ClassMerger, Renderer, and Document timings';
    }

    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $io->writeln('SERIXA profile');
        $io->writeln(str_repeat('─', 48));

        $compiler = new ThemeCompiler();
        $compiler->forget();
        $t0 = hrtime(true);
        $rules = $compiler->compile();
        $compileCold = $this->ms($t0);
        $t0 = hrtime(true);
        $compiler->compile();
        $compileWarm = $this->ms($t0);

        $merger = new ClassMerger();
        $merger->clearCache();
        $sample = ['sx-btn', 'px-4', 'px-2', 'text-sm', 'flex', 'items-center'];
        $t0 = hrtime(true);
        for ($i = 0; $i < 1000; ++$i) {
            $merger->merge($sample);
        }
        $mergeCold = $this->ms($t0);
        $t0 = hrtime(true);
        for ($i = 0; $i < 1000; ++$i) {
            $merger->merge($sample);
        }
        $mergeWarm = $this->ms($t0);

        $theme = new DefaultTheme();
        $t0 = hrtime(true);
        for ($i = 0; $i < 1000; ++$i) {
            $theme->classes('button', ['variant' => 'primary', 'size' => 'md']);
        }
        $themeMs = $this->ms($t0);

        RenderPipeline::resetDefault();
        $renderer = new Renderer();
        $tree = Column::make(
            Text::make('Profile'),
            Button::make('Action')->primary(),
        );
        $t0 = hrtime(true);
        $html = '';
        for ($i = 0; $i < 200; ++$i) {
            $html = $renderer->render($tree);
        }
        $renderMs = $this->ms($t0);

        $doc = Document::make()->title('Profile')->body($tree);
        $t0 = hrtime(true);
        $docHtml = DocumentRenderer::make()->render($doc);
        $docMs = $this->ms($t0);

        $io->writeln(sprintf('  ThemeCompiler cold     %8.3f ms  (%d rules)', $compileCold, count($rules)));
        $io->writeln(sprintf('  ThemeCompiler warm     %8.3f ms', $compileWarm));
        $io->writeln(sprintf('  ClassMerger x1000 cold %8.3f ms', $mergeCold));
        $io->writeln(sprintf('  ClassMerger x1000 warm %8.3f ms', $mergeWarm));
        $io->writeln(sprintf('  Theme map x1000        %8.3f ms', $themeMs));
        $io->writeln(sprintf('  Renderer x200          %8.3f ms  (avg %.3f ms)', $renderMs, $renderMs / 200));
        $io->writeln(sprintf('  Document once          %8.3f ms  (%d bytes)', $docMs, strlen($docHtml)));
        $io->writeln(sprintf('  Sample HTML bytes      %8d', strlen($html)));
        $io->writeln(sprintf('  Peak memory            %s', $this->formatBytes(memory_get_peak_usage(true))));
        $io->writeln(str_repeat('─', 48));
        $io->success('Profile complete.');

        return 0;
    }

    private function ms(int $startHrtime): float
    {
        return (hrtime(true) - $startHrtime) / 1_000_000;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        return round($bytes / 1024 / 1024, 2) . ' MB';
    }
}
