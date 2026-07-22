<?php

declare(strict_types=1);

namespace Serixa\Cli\Commands;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Pipeline\RenderPipeline;
use Serixa\Styling\ThemeCompiler;
use Serixa\Support\ClassMerger;
use Serixa\Theme\ThemeManager;

/**
 * serixa cache:clear — reset in-process framework caches.
 */
final class CacheClearCommand extends Command
{
    public function name(): string
    {
        return 'cache:clear';
    }

    public function description(): string
    {
        return 'Clear ClassMerger, ThemeCompiler, ThemeManager, and RenderPipeline caches';
    }

    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $io->writeln('SERIXA cache:clear');
        $io->writeln(str_repeat('─', 40));

        (new ClassMerger())->clearCache();
        ClassMerger::clearConflictGroupCache();
        $io->writeln('  [OK] ClassMerger');

        (new ThemeCompiler())->forget();
        $io->writeln('  [OK] ThemeCompiler');

        ThemeManager::reset();
        $io->writeln('  [OK] ThemeManager');

        RenderPipeline::resetDefault();
        $io->writeln('  [OK] RenderPipeline');

        $io->writeln(str_repeat('─', 40));
        $io->success('In-process caches cleared.');

        return 0;
    }
}
