<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;
use Serixa\DevTools\Theme\DevToolsTheme;
use Serixa\Theme\ThemeManager;

final class ThemeResolutionCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('theme_resolution');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        $theme = \Serixa\DevTools\DevTools::themeSnapshot() ?? ThemeManager::current();

        return [
            'class' => $theme::class,
            'devtoolsWrapped' => $theme instanceof DevToolsTheme,
        ];
    }
}
