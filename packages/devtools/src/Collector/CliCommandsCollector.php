<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\Cli\CommandRegistry;
use Serixa\DevTools\Support\CollectorContext;

final class CliCommandsCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('cli_commands');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        if (! class_exists(CommandRegistry::class)) {
            return ['available' => false, 'commands' => []];
        }

        return [
            'available' => true,
            'commands' => CommandRegistry::default()->names(),
        ];
    }
}
