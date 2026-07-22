<?php

declare(strict_types=1);

namespace Serixa\DevTools\Cli;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Theme\ThemeManager;

final class DoctorCommand extends Command
{
    public function name(): string
    {
        return 'doctor';
    }

    public function description(): string
    {
        return 'Run SERIXA DevTools health checks';
    }

    /**
     * @param list<string> $arguments
     * @param array<string, string|bool> $options
     */
    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $failures = 0;
        $warnings = 0;

        $failures += $this->report($io, $this->checkPhpVersion(), $warnings);
        $failures += $this->report($io, $this->checkExtension('json'), $warnings);
        $failures += $this->report($io, $this->checkExtension('mbstring'), $warnings);
        $failures += $this->report($io, $this->checkFrameworkAutoload(), $warnings);
        $failures += $this->report($io, $this->checkThemeManager(), $warnings);

        $io->writeln('');
        $io->writeln(sprintf('Summary: %d failure(s), %d warning(s)', $failures, $warnings));

        return $failures > 0 ? 1 : 0;
    }

    /**
     * @return array{level: string, message: string}
     */
    private function checkPhpVersion(): array
    {
        if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
            return ['level' => 'OK', 'message' => sprintf('PHP >= 8.2 (%s)', PHP_VERSION)];
        }

        return ['level' => 'FAIL', 'message' => sprintf('PHP >= 8.2 required (found %s)', PHP_VERSION)];
    }

    /**
     * @return array{level: string, message: string}
     */
    private function checkExtension(string $extension): array
    {
        if (extension_loaded($extension)) {
            return ['level' => 'OK', 'message' => sprintf('Extension %s loaded', $extension)];
        }

        return ['level' => 'FAIL', 'message' => sprintf('Extension %s missing', $extension)];
    }

    /**
     * @return array{level: string, message: string}
     */
    private function checkFrameworkAutoload(): array
    {
        if (class_exists(\Serixa\Rendering\Renderer::class)) {
            return ['level' => 'OK', 'message' => 'Serixa framework autoload available'];
        }

        return ['level' => 'FAIL', 'message' => 'Serixa framework autoload unavailable'];
    }

    /**
     * @return array{level: string, message: string}
     */
    private function checkThemeManager(): array
    {
        try {
            $themeClass = ThemeManager::current()::class;

            return ['level' => 'OK', 'message' => sprintf('ThemeManager resolves (%s)', $themeClass)];
        } catch (\Throwable $exception) {
            return ['level' => 'FAIL', 'message' => sprintf('ThemeManager failed: %s', $exception->getMessage())];
        }
    }

    /**
     * @param array{level: string, message: string} $result
     */
    private function report(InputOutput $io, array $result, int &$warnings): int
    {
        $io->writeln(sprintf('[%s] %s', $result['level'], $result['message']));

        if ($result['level'] === 'WARN') {
            ++$warnings;
        }

        return $result['level'] === 'FAIL' ? 1 : 0;
    }
}
