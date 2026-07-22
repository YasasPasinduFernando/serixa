<?php

declare(strict_types=1);

namespace Serixa\DevTools\Cli;

use Serixa\Cli\Command;
use Serixa\Cli\InputOutput;
use Serixa\Component\Text;
use Serixa\Rendering\Renderer;

final class BenchmarkCommand extends Command
{
    public function name(): string
    {
        return 'benchmark';
    }

    public function description(): string
    {
        return 'Benchmark Text widget rendering throughput';
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

        $times = isset($options['times']) ? max(1, (int) $options['times']) : 100;
        $renderer = new Renderer();
        $widget = Text::make('profile');
        $samples = [];

        for ($i = 0; $i < $times; ++$i) {
            $startedAt = hrtime(true);
            $renderer->render($widget);
            $samples[] = (hrtime(true) - $startedAt) / 1_000_000;
        }

        sort($samples);

        $total = array_sum($samples);
        $avg = $total / $times;
        $min = $samples[0];
        $max = $samples[$times - 1];

        $io->writeln(sprintf('Benchmark: Text::make("profile") x %d', $times));
        $io->writeln(sprintf('Average: %.3f ms', $avg));
        $io->writeln(sprintf('Min:     %.3f ms', $min));
        $io->writeln(sprintf('Max:     %.3f ms', $max));
        $io->writeln(sprintf('Total:   %.3f ms', $total));

        return 0;
    }
}
