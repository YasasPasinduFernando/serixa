<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\AuthUi\Pages\ForgotPasswordCard;
use Serixa\AuthUi\Pages\LoginCard;
use Serixa\AuthUi\Pages\RegisterCard;
use Serixa\AuthUi\Pages\ResetPasswordCard;
use Serixa\AuthUi\Theme\AuthTheme;
use Serixa\Contracts\WidgetInterface;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class AuthPageCatalogTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        ThemeManager::reset();
        IconRegistry::reset();
        ThemeManager::set(new AuthTheme(new DefaultTheme()));
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
    public static function pageProvider(): array
    {
        return [
            'login' => [static fn () => LoginCard::make(), ['password', 'email', 'form']],
            'register' => [static fn () => RegisterCard::make(), ['password', 'form']],
            'forgot' => [static fn () => ForgotPasswordCard::make(), ['email', 'form']],
            'reset' => [static fn () => ResetPasswordCard::make(), ['password', 'form']],
        ];
    }

    /**
     * @param callable(): WidgetInterface $factory
     * @param list<string> $needles
     */
    #[DataProvider('pageProvider')]
    public function testAuthPagesRenderForms(callable $factory, array $needles): void
    {
        $html = strtolower($this->renderer->render($factory()));
        foreach ($needles as $needle) {
            $this->assertStringContainsString($needle, $html);
        }
    }
}
