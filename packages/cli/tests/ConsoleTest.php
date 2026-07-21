<?php

declare(strict_types=1);

namespace Serixa\Cli\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Cli\Application;
use Serixa\Cli\Command;
use Serixa\Cli\CommandRegistry;
use Serixa\Cli\InputOutput;
use Serixa\Cli\Plugin;
use Serixa\Cli\Scaffold\TemplateEngine;
use Serixa\Cli\Scaffold\WorkingDirectory;
use Serixa\Exception\InvalidArgumentException;

final class ConsoleTest extends TestCase
{
    private string $tmp = '';

    private string $cliRoot = '';

    private string $frameworkRoot = '';

    protected function setUp(): void
    {
        parent::setUp();
        CommandRegistry::resetDefault();
        $this->cliRoot = dirname(__DIR__);
        $this->frameworkRoot = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'framework';
        $this->tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'serixa-cli-' . bin2hex(random_bytes(4));
        mkdir($this->tmp, 0777, true);
    }

    protected function tearDown(): void
    {
        CommandRegistry::resetDefault();
        $this->deleteTree($this->tmp);
        parent::tearDown();
    }

    public function testVersionHelpAndList(): void
    {
        $app = new Application(cliRoot: $this->cliRoot, frameworkRoot: $this->frameworkRoot);
        $io = new InputOutput(true);
        $this->assertSame(0, $app->run(['serixa', 'version'], $io));
        $this->assertStringContainsString('0.1.0', $io->output());

        $io = new InputOutput(true);
        $this->assertSame(0, $app->run(['serixa', 'help'], $io));
        $this->assertStringContainsString('make:page', $io->output());

        $io = new InputOutput(true);
        $this->assertSame(0, $app->run(['serixa', 'list'], $io));
        $this->assertStringContainsString('new', $io->output());
        $this->assertStringContainsString('build', $io->output());
    }

    public function testCommandRegistrationAndPluginFacade(): void
    {
        $app = new Application(cliRoot: $this->cliRoot, frameworkRoot: $this->frameworkRoot);
        $app->registerDefaults();
        $this->assertTrue($app->registry()->has('docs'));

        $stub = new class () extends Command {
            public function name(): string
            {
                return 'demo:hello';
            }

            public function description(): string
            {
                return 'Demo';
            }

            public function handle(array $arguments, array $options, InputOutput $io): int
            {
                $io->writeln('hello');

                return 0;
            }
        };

        Plugin::registerCommand($stub);
        $this->assertTrue(CommandRegistry::default()->has('demo:hello'));

        $this->expectException(InvalidArgumentException::class);
        Plugin::registerCommand($stub);
    }

    public function testTemplateEngineRendersAndRejectsOverwrite(): void
    {
        $engine = new TemplateEngine($this->cliRoot . DIRECTORY_SEPARATOR . 'templates');
        $html = $engine->render('page/Page.stub', [
            'ClassName' => 'DashboardPage',
            'Namespace' => 'App\\Pages',
            'Name' => 'Dashboard',
        ]);
        $this->assertStringContainsString('class DashboardPage', $html);
        $this->assertStringContainsString('namespace App\\Pages', $html);

        $target = $this->tmp . DIRECTORY_SEPARATOR . 'DashboardPage.php';
        $engine->writeStub('page/Page.stub', $target, [
            'ClassName' => 'DashboardPage',
            'Namespace' => 'App\\Pages',
            'Name' => 'Dashboard',
        ]);
        $this->assertFileExists($target);

        $this->expectException(InvalidArgumentException::class);
        $engine->writeStub('page/Page.stub', $target, [
            'ClassName' => 'DashboardPage',
            'Namespace' => 'App\\Pages',
            'Name' => 'Dashboard',
        ]);
    }

    public function testNewProjectAndMakeCommands(): void
    {
        $cwd = getcwd();
        chdir($this->tmp);

        try {
            $app = new Application(cliRoot: $this->cliRoot, frameworkRoot: $this->frameworkRoot);
            $io = new InputOutput(true);
            $code = $app->run(['serixa', 'new', 'blog'], $io);
            $this->assertSame(0, $code, $io->output());
            $project = $this->tmp . DIRECTORY_SEPARATOR . 'blog';
            $this->assertDirectoryExists($project);
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'composer.json');
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'HomePage.php');
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'PostsPage.php');
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'serixa.js');

            chdir($project);
            WorkingDirectory::set($project);

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'make:page', 'Dashboard'], $io), $io->output());
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'DashboardPage.php');

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'make:component', 'UserCard'], $io), $io->output());
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'UserCard.php');

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'make:layout', 'Admin'], $io), $io->output());
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'AdminLayout.php');

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'make:theme', 'Dark'], $io), $io->output());
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'DarkTheme.php');

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'make:plugin', 'Charts'], $io), $io->output());
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'ChartsPlugin.php');

            $io = new InputOutput(true);
            $this->assertSame(1, $app->run(['serixa', 'make:page', 'Dashboard'], $io));
            $this->assertStringContainsString('Refusing to overwrite', $io->output());
        } finally {
            chdir($cwd ?: $this->cliRoot);
        }
    }

    public function testDocsBuildAndDevOnce(): void
    {
        $cwd = getcwd();
        chdir($this->tmp);

        try {
            $app = new Application(cliRoot: $this->cliRoot, frameworkRoot: $this->frameworkRoot);
            $app->run(['serixa', 'new', 'crm'], new InputOutput(true));
            $project = $this->tmp . DIRECTORY_SEPARATOR . 'crm';
            chdir($project);
            WorkingDirectory::set($project);

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'docs'], $io), $io->output());
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'generated' . DIRECTORY_SEPARATOR . 'widgets.md');
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'generated' . DIRECTORY_SEPARATOR . '.cache.json');

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'build'], $io), $io->output());
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'manifest.json');
            $this->assertFileExists($project . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'tokens.json');

            $io = new InputOutput(true);
            $this->assertSame(0, $app->run(['serixa', 'dev', '--once'], $io), $io->output());
            $this->assertStringContainsString('rebuilding', $io->output());
        } finally {
            chdir($cwd ?: $this->cliRoot);
        }
    }

    private function deleteTree(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($path);
    }
}
