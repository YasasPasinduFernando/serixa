<?php

declare(strict_types=1);

namespace Serixa\Admin\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Theme\AdminTheme;
use Serixa\Contracts\WidgetInterface;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class AdminCatalogTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        ThemeManager::reset();
        IconRegistry::reset();
        ThemeManager::set(new AdminTheme(new DefaultTheme()));
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        parent::tearDown();
    }

    /**
     * @return array<string, array{0: callable(): WidgetInterface, 1: list<string>}>
     */
    public static function widgetProvider(): array
    {
        return [
            'info' => [static fn () => InfoCard::make('Body text')->title('Account'), ['Account', 'Body text']],
            'metric' => [static fn () => MetricCard::make()->title('Users')->value('42'), ['Users', '42']],
            'status' => [static fn () => StatusBadge::make('Active')->status('success'), ['Active']],
            'empty' => [static fn () => EmptyState::make()->title('No data')->description('Try again'), ['No data', 'Try again']],
        ];
    }

    /**
     * @param callable(): WidgetInterface $factory
     * @param list<string> $needles
     */
    #[DataProvider('widgetProvider')]
    public function testAdminWidgetsRender(callable $factory, array $needles): void
    {
        $html = $this->renderer->render($factory());
        foreach ($needles as $needle) {
            $this->assertStringContainsString($needle, $html);
        }
    }
}
