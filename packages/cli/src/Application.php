<?php

declare(strict_types=1);

namespace Serixa\Cli;

use Serixa\Cli\Scaffold\TemplateEngine;
use Serixa\Cli\Scaffold\WorkingDirectory;
use Serixa\Cli\Support\FrameworkLocator;

/**
 * SERIXA CLI - scaffolding & DX tooling (independent from the HTML render pipeline).
 */
final class Application
{
    public const VERSION = '0.1.1';

    private ?TemplateEngine $templateEngine = null;

    private bool $defaultsRegistered = false;

    public function __construct(
        private readonly CommandRegistry $registry = new CommandRegistry(),
        private readonly ?string $cliRoot = null,
        private readonly ?string $frameworkRoot = null,
    ) {
    }

    public function registry(): CommandRegistry
    {
        return $this->registry;
    }

    /**
     * Root of the serixa/cli package (templates live here).
     */
    public function cliRoot(): string
    {
        return $this->cliRoot ?? dirname(__DIR__);
    }

    /**
     * Installed serixa/framework package root (runtime assets / examples).
     */
    public function frameworkRoot(): string
    {
        return $this->frameworkRoot ?? FrameworkLocator::root();
    }

    public function templateEngine(): TemplateEngine
    {
        return $this->templateEngine ??= new TemplateEngine($this->cliRoot() . DIRECTORY_SEPARATOR . 'templates');
    }

    public function registerDefaults(): self
    {
        if ($this->defaultsRegistered) {
            return $this;
        }

        foreach ($this->defaultCommands() as $command) {
            if (! $this->registry->has($command->name())) {
                $this->registry->register($command);
            }
        }

        $this->defaultsRegistered = true;

        return $this;
    }

    public function syncDefaultRegistry(): self
    {
        $this->registerDefaults();

        foreach ($this->registry->all() as $command) {
            if (! CommandRegistry::default()->has($command->name())) {
                CommandRegistry::default()->register($command);
            }
        }

        return $this;
    }

    /**
     * @param list<string> $argv
     */
    public function run(array $argv, ?InputOutput $io = null): int
    {
        $io ??= new InputOutput();
        $args = array_values(array_slice($argv, 1));

        if ($args !== [] && in_array($args[0], ['--version', '-V', 'version'], true)) {
            $io->writeln('SERIXA CLI ' . self::VERSION);

            return 0;
        }

        $this->registerDefaults();

        if ($args === []) {
            $this->printBanner($io);
            $io->writeln('Usage: serixa <command> [arguments] [options]');
            $io->writeln('Run `serixa list` or `serixa help` for available commands.');
            $io->writeln('');
            $io->writeln('Quick start:');
            $io->writeln('  serixa new blog');
            $io->writeln('  cd blog && composer install');
            $io->writeln('  php -S localhost:8000 -t public');
            $io->writeln('');
            $io->writeln('Package: serixa/cli - Runtime: serixa/framework');

            return 0;
        }

        $commandName = array_shift($args) ?? '';

        if (in_array($commandName, ['--help', '-h', 'help'], true)) {
            return $this->printHelp($io, $args[0] ?? null);
        }

        if ($commandName === 'list') {
            return $this->printList($io);
        }

        [$arguments, $options] = $this->parseArgs($args);
        $command = $this->registry->get($commandName);

        if ($command === null) {
            $io->error(sprintf('Unknown command "%s". Run `serixa list` or `serixa help`.', $commandName));
            $io->info('Tip: try `serixa new blog` to scaffold an app.');

            return 1;
        }

        WorkingDirectory::set(getcwd() ?: $this->cliRoot());

        return $command->handle($arguments, $options, $io);
    }

    /**
     * @return list<CommandInterface>
     */
    private function defaultCommands(): array
    {
        $frameworkRoot = $this->frameworkRoot();
        $templates = $this->templateEngine();

        return [
            new Commands\NewProjectCommand($templates, $frameworkRoot),
            new Commands\MakePageCommand($templates),
            new Commands\MakeComponentCommand($templates),
            new Commands\MakeLayoutCommand($templates),
            new Commands\MakeThemeCommand($templates),
            new Commands\MakePluginCommand($templates),
            new Commands\DocsCommand($frameworkRoot),
            new Commands\DevCommand($frameworkRoot),
            new Commands\BuildCommand($frameworkRoot),
        ];
    }

    /**
     * @param list<string> $args
     * @return array{0: list<string>, 1: array<string, string|bool>}
     */
    private function parseArgs(array $args): array
    {
        $arguments = [];
        $options = [];

        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                $body = substr($arg, 2);

                if (str_contains($body, '=')) {
                    [$key, $value] = explode('=', $body, 2);
                    $options[$key] = $value;
                } else {
                    $options[$body] = true;
                }

                continue;
            }

            $arguments[] = $arg;
        }

        return [$arguments, $options];
    }

    private function printBanner(InputOutput $io): void
    {
        $io->writeln('SERIXA CLI ' . self::VERSION);
        $io->writeln('Scaffolding & DX tooling for serixa/framework');
        $io->writeln('');
    }

    private function printHelp(InputOutput $io, ?string $commandName): int
    {
        $this->printBanner($io);

        if ($commandName !== null) {
            $command = $this->registry->get($commandName);

            if ($command === null) {
                $io->error(sprintf('Unknown command "%s".', $commandName));
                $io->info('Run `serixa list` to see commands.');

                return 1;
            }

            $io->writeln(sprintf('Command: %s', $command->name()));
            $io->writeln(sprintf('  %s', $command->description()));
            $io->writeln('');
            $this->printCommandUsage($io, $command->name());

            return 0;
        }

        $io->writeln('Usage:');
        $io->writeln('  serixa <command> [arguments] [options]');
        $io->writeln('');
        $io->writeln('Global:');
        $io->writeln('  version, --version, -V     Print CLI version (fast path)');
        $io->writeln('  help [command], --help, -h Show help');
        $io->writeln('  list                       List commands');
        $io->writeln('');
        $this->printList($io);
        $io->writeln('');
        $io->writeln('Learn more: docs/getting-started.md - docs/cli.md');

        return 0;
    }

    private function printCommandUsage(InputOutput $io, string $name): void
    {
        $examples = match ($name) {
            'new' => [
                'serixa new blog',
                'serixa new my-app --type=crm',
                'Presets: blog, crm, ecommerce (use --type= for custom folder names)',
                'Avoid creating projects inside the framework tree (path-repo recursion).',
            ],
            'make:page' => ['serixa make:page Articles', 'Creates pages/ArticlesPage.php'],
            'make:component' => ['serixa make:component ArticleCard', 'Creates components/ArticleCard.php'],
            'make:layout' => ['serixa make:layout Site', 'Creates layouts/SiteLayout.php'],
            'make:theme' => ['serixa make:theme Dark', 'Creates themes/DarkTheme.php'],
            'make:plugin' => ['serixa make:plugin Charts', 'Creates plugins/ChartsPlugin.php'],
            'docs' => ['serixa docs', 'Writes docs/generated/*'],
            'build' => ['serixa build', 'Tokens, runtime copy, manifest, docs cache'],
            'dev' => ['serixa dev --once', 'Single watch cycle (omit --once to poll)'],
            default => [],
        };

        if ($examples === []) {
            return;
        }

        $io->writeln('Examples:');

        foreach ($examples as $line) {
            $io->writeln('  ' . $line);
        }
    }

    private function printList(InputOutput $io): int
    {
        $io->writeln('Available commands:');

        foreach ($this->registry->all() as $command) {
            $io->writeln(sprintf('  %-22s %s', $command->name(), $command->description()));
        }

        return 0;
    }
}
