<?php

declare(strict_types=1);

namespace Serixa\Cli\Commands;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Cli\Scaffold\WorkingDirectory;

/**
 * serixa doctor — environment and project health checks.
 */
final class DoctorCommand extends Command
{
    public function __construct(
        private readonly string $frameworkRoot,
    ) {
    }

    public function name(): string
    {
        return 'doctor';
    }

    public function description(): string
    {
        return 'Check PHP, extensions, framework path, and project assets';
    }

    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $cwd = WorkingDirectory::get();
        $ok = true;

        $io->writeln('SERIXA doctor');
        $io->writeln(str_repeat('─', 40));

        $checks = [
            'PHP >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'ext-json' => extension_loaded('json'),
            'ext-mbstring' => extension_loaded('mbstring'),
            'Framework root readable' => is_dir($this->frameworkRoot),
            'Foundation CSS present' => is_file($this->frameworkRoot . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'serixa.css'),
            'Working directory' => is_dir($cwd),
        ];

        $publicCss = $cwd . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'serixa.css';
        $checks['public/assets/serixa.css'] = is_file($publicCss);
        $checks['composer.json in cwd'] = is_file($cwd . DIRECTORY_SEPARATOR . 'composer.json');

        foreach ($checks as $label => $pass) {
            $mark = $pass ? 'OK' : '!!';
            $io->writeln(sprintf('  [%s] %s', $mark, $label));
            if (! $pass && ! str_contains($label, 'public/assets') && ! str_contains($label, 'composer.json')) {
                $ok = false;
            }
        }

        if (! is_file($publicCss)) {
            $io->info('Tip: run `serixa build` to generate public/assets/serixa.css');
        }

        $io->writeln(str_repeat('─', 40));
        $io->writeln($ok ? 'Doctor finished — core checks passed.' : 'Doctor finished — fix failures above.');

        return $ok ? 0 : 1;
    }
}
