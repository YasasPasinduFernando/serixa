<?php

declare(strict_types=1);

namespace Serixa\DevTools\Cli;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\DevTools\DevTools;
use Serixa\Theme\ThemeManager;

final class InspectCommand extends Command
{
    public function name(): string
    {
        return 'inspect';
    }

    public function description(): string
    {
        return 'Inspect SERIXA widget/theme environment';
    }

    /**
     * @param list<string> $arguments
     * @param array<string, string|bool> $options
     */
    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $io->writeln('SERIXA DevTools — environment inspect');
        $io->writeln(sprintf('PHP version: %s', PHP_VERSION));
        $io->writeln(sprintf('Serixa framework: %s', class_exists(\Serixa\Rendering\Renderer::class) ? 'present' : 'missing'));
        $io->writeln(sprintf('ThemeManager theme: %s', ThemeManager::current()::class));
        $io->writeln(sprintf('Memory usage: %s', $this->formatBytes(memory_get_usage(true))));
        $io->writeln(sprintf('Memory peak: %s', $this->formatBytes(memory_get_peak_usage(true))));
        $io->writeln(sprintf('DevTools enabled: %s', DevTools::enabled() ? 'yes' : 'no'));

        return 0;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1_048_576) {
            return sprintf('%.2f MB (%d bytes)', $bytes / 1_048_576, $bytes);
        }

        if ($bytes >= 1024) {
            return sprintf('%.2f KB (%d bytes)', $bytes / 1024, $bytes);
        }

        return sprintf('%d bytes', $bytes);
    }
}
