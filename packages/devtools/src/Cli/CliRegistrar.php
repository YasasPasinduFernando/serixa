<?php

declare(strict_types=1);

namespace Serixa\DevTools\Cli;

use Serixa\Cli\Command;
use Serixa\Cli\CommandRegistry;
use Serixa\Cli\Plugin;

/**
 * Registers DevTools CLI commands with the global SERIXA CLI registry.
 */
final class CliRegistrar
{
    public static function register(): void
    {
        $registry = CommandRegistry::default();

        foreach (self::commands() as $command) {
            if ($registry->has($command->name())) {
                continue;
            }

            Plugin::registerCommand($command);
        }
    }

    /**
     * @return list<Command>
     */
    private static function commands(): array
    {
        return [
            new InspectCommand(),
            new ProfileCommand(),
            new DoctorCommand(),
            new BenchmarkCommand(),
        ];
    }
}
