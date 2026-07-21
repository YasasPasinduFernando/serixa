<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use NovaPHP\Component\Button;
use NovaPHP\Component\Column;
use NovaPHP\Component\Text;
use NovaPHP\Container\Container;
use NovaPHP\Plugin\PluginManager;
use NovaPHP\Plugin\RuntimePlugin;
use NovaPHP\Registry\WidgetRegistry;
use NovaPHP\Rendering\Renderer;
use NovaPHP\Runtime\WidgetContext;
use NovaPHP\Theme\DefaultTheme;

$registry = WidgetRegistry::withDefaults();
$container = new Container();
$container->instance(DefaultTheme::class, new DefaultTheme());

$plugins = new PluginManager($container, $registry);
$plugins->register(new class () implements RuntimePlugin {
    public function name(): string
    {
        return 'runtime-demo';
    }

    public function boot(Container $container, WidgetRegistry $registry): void
    {
        $container->instance('app.name', 'SERIXA Runtime');
    }
});

$context = WidgetContext::defaults()
    ->withEnvironment('testing')
    ->withColorMode('light')
    ->withFeatureFlags(['runtime_core' => true]);

$renderer = new Renderer(null, $context);

$ui = Column::make([
    Text::make('Runtime Core')->as('h1')->size('xl')->weight('bold'),
    Text::make('App: ' . $container->resolve('app.name'))->tone('muted'),
    Text::make('Registered widgets: ' . (string) count($registry->all()))->as('p'),
    Button::make('Continue')->primary(),
])->gap(3)->alignCenter();

echo $renderer->render($ui), PHP_EOL;
echo 'Pipeline passes: ' . implode(', ', array_map(
    static fn ($pass) => $pass->name(),
    $renderer->pipeline()->passes(),
)), PHP_EOL;
