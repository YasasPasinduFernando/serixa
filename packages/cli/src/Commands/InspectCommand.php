<?php

declare(strict_types=1);

namespace Serixa\Cli\Commands;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Cli\Scaffold\WorkingDirectory;
use Serixa\Styling\StyleSheet;
use Serixa\Theme\DefaultTheme;

/**
 * serixa inspect — print project / theme / asset summary.
 */
final class InspectCommand extends Command
{
    public function __construct(
        private readonly string $frameworkRoot,
    ) {
    }

    public function name(): string
    {
        return 'inspect';
    }

    public function description(): string
    {
        return 'Inspect cwd, framework path, theme tokens, and stylesheet URL';
    }

    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        $cwd = WorkingDirectory::get();
        $theme = new DefaultTheme();

        $io->writeln('SERIXA inspect');
        $io->writeln(str_repeat('─', 40));
        $io->writeln('  CWD            ' . $cwd);
        $io->writeln('  Framework      ' . $this->frameworkRoot);
        $io->writeln('  Stylesheet URL ' . StyleSheet::PUBLIC_URL);
        $io->writeln('  Foundation     ' . StyleSheet::foundationPath());
        $io->writeln('  Theme class    ' . $theme::class);
        $io->writeln('  Sample button  ' . implode(' ', $theme->buttonPrimary()));
        $io->writeln(str_repeat('─', 40));

        $manifest = $cwd . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'theme-manifest.json';
        if (is_file($manifest)) {
            $data = json_decode((string) file_get_contents($manifest), true);
            if (is_array($data)) {
                $io->writeln(sprintf('  Manifest rules %s', (string) ($data['selector_count'] ?? '?')));
                $io->writeln(sprintf('  Manifest tokens %s', (string) count($data['tokens'] ?? [])));
            }
        } else {
            $io->info('No theme-manifest.json yet — run `serixa build`.');
        }

        return 0;
    }
}
