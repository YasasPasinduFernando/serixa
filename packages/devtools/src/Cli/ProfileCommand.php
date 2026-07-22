<?php

declare(strict_types=1);

namespace Serixa\DevTools\Cli;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Component\Text;
use Serixa\Rendering\Renderer;

final class ProfileCommand extends Command
{
    public function name(): string
    {
        return 'profile';
    }

    public function description(): string
    {
        return 'Profile a tiny Text widget render';
    }

    /**
     * @param list<string> $arguments
     * @param array<string, string|bool> $options
     */
    public function handle(array $arguments, array $options, InputOutput $io): int
    {
        if (! class_exists(Renderer::class)) {
            $io->error('Serixa Renderer is not available.');

            return 1;
        }

        $memoryBefore = memory_get_usage(true);
        $startedAt = hrtime(true);

        $html = (new Renderer())->render(Text::make('profile'));

        $durationMs = (hrtime(true) - $startedAt) / 1_000_000;
        $memoryAfter = memory_get_usage(true);
        $memoryDelta = max(0, $memoryAfter - $memoryBefore);

        $payload = [
            'durationMs' => round($durationMs, 3),
            'memoryBytes' => $memoryAfter,
            'memoryDeltaBytes' => $memoryDelta,
            'memoryPeakBytes' => memory_get_peak_usage(true),
            'htmlLength' => strlen($html),
        ];

        if (isset($options['json']) && $options['json']) {
            $io->writeln(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}');

            return 0;
        }

        $io->writeln(sprintf('Render duration: %.3f ms', $payload['durationMs']));
        $io->writeln(sprintf('Memory usage: %d bytes (delta %+d bytes)', $payload['memoryBytes'], $payload['memoryDeltaBytes']));
        $io->writeln(sprintf('Memory peak: %d bytes', $payload['memoryPeakBytes']));
        $io->writeln(sprintf('HTML length: %d bytes', $payload['htmlLength']));

        return 0;
    }
}
