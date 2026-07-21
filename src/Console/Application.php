<?php

declare(strict_types=1);

namespace Serixa\Console;

use Serixa\Console\Scaffold\TemplateEngine;
use Serixa\Console\Scaffold\WorkingDirectory;

/**
 * SERIXA CLI application — independent from the HTML render pipeline.
 */
final class Application
{
    public const VERSION = '0.10.0';

    public function __construct(
        private readonly CommandRegistry $registry = new CommandRegistry(),
        private readonly ?string $frameworkRoot = null,
    ) {
    }

    public function registry(): CommandRegistry
    {
        return $this->registry;
    }

    public function frameworkRoot(): string
    {
        return $this->frameworkRoot ?? dirname(__DIR__, 2);
    }

    public function templateEngine(): TemplateEngine
    {
        return new TemplateEngine($this->frameworkRoot() . DIRECTORY_SEPARATOR . 'templates');
    }

    /**
     * Register built-in commands on this application instance.
     */
    public function registerDefaults(): self
    {
        foreach ($this->defaultCommands() as $command) {
            if (! $this->registry->has($command->name())) {
                $this->registry->register($command);
            }
        }

        return $this;
    }

    /**
     * Mirror built-ins onto the process-wide default registry (plugin discovery).
     */
    public function syncDefaultRegistry(): self
    {
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
        $this->registerDefaults();

        $args = array_values(array_slice($argv, 1));

        if ($args === []) {
            $this->printBanner($io);
            $io->writeln('Usage: serixa <command> [arguments] [options]');
            $io->writeln('Run `serixa list` or `serixa help` for available commands.');

            return 0;
        }

        $commandName = array_shift($args) ?? '';

        if (in_array($commandName, ['--version', '-V', 'version'], true)) {
            $io->writeln('SERIXA CLI ' . self::VERSION);

            return 0;
        }

        if (in_array($commandName, ['--help', '-h', 'help'], true)) {
            return $this->printHelp($io, $args[0] ?? null);
        }

        if ($commandName === 'list') {
            return $this->printList($io);
        }

        [$arguments, $options] = $this->parseArgs($args);
        $command = $this->registry->get($commandName);

        if ($command === null) {
            $io->error(sprintf('Unknown command "%s". Run `serixa list`.', $commandName));

            return 1;
        }

        WorkingDirectory::set(getcwd() ?: $this->frameworkRoot());

        return $command->handle($arguments, $options, $io);
    }

    /**
     * @return list<CommandInterface>
     */
    private function defaultCommands(): array
    {
        $root = $this->frameworkRoot();
        $templates = $this->templateEngine();

        return [
            new Commands\NewProjectCommand($templates, $root),
            new Commands\MakePageCommand($templates),
            new Commands\MakeComponentCommand($templates),
            new Commands\MakeLayoutCommand($templates),
            new Commands\MakeThemeCommand($templates),
            new Commands\MakePluginCommand($templates),
            new Commands\DocsCommand($root),
            new Commands\DevCommand($root),
            new Commands\BuildCommand($root),
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
        $io->writeln('PHP-first UI framework — scaffolding & DX tooling');
        $io->writeln('');
    }

    private function printHelp(InputOutput $io, ?string $commandName): int
    {
        $this->printBanner($io);

        if ($commandName !== null) {
            $command = $this->registry->get($commandName);

            if ($command === null) {
                $io->error(sprintf('Unknown command "%s".', $commandName));

                return 1;
            }

            $io->writeln(sprintf('  %s', $command->name()));
            $io->writeln(sprintf('    %s', $command->description()));

            return 0;
        }

        $io->writeln('Usage:');
        $io->writeln('  serixa <command> [arguments] [options]');
        $io->writeln('');
        $io->writeln('Global:');
        $io->writeln('  version, --version, -V');
        $io->writeln('  help [command], --help, -h');
        $io->writeln('  list');
        $io->writeln('');
        $this->printList($io);

        return 0;
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
