<?php

declare(strict_types=1);

namespace Serixa\Forms\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Serixa\Contracts\WidgetInterface;
use Serixa\Forms\Field\Checkbox;
use Serixa\Forms\Field\Input;
use Serixa\Forms\Field\Select;
use Serixa\Forms\Field\Textarea;
use Serixa\Forms\Theme\FormsTheme;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class FormFieldCatalogTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        ThemeManager::reset();
        ThemeManager::set(new FormsTheme(new DefaultTheme()));
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        ThemeManager::reset();
        parent::tearDown();
    }

    /**
     * @return array<string, array{0: callable(): WidgetInterface, 1: list<string>}>
     */
    public static function fieldProvider(): array
    {
        return [
            'input' => [static fn () => Input::make('email')->label('Email')->type('email'), ['type="email"', 'Email', 'name="email"']],
            'textarea' => [static fn () => Textarea::make('bio')->label('Bio'), ['<textarea', 'Bio', 'name="bio"']],
            'checkbox' => [static fn () => Checkbox::make('agree')->label('Agree'), ['type="checkbox"', 'Agree']],
            'select' => [static fn () => Select::make('plan')->label('Plan')->options(['a' => 'A', 'b' => 'B']), ['<select', 'Plan', 'A']],
        ];
    }

    /**
     * @param callable(): WidgetInterface $factory
     * @param list<string> $needles
     */
    #[DataProvider('fieldProvider')]
    public function testFormFieldsRenderLabels(callable $factory, array $needles): void
    {
        $html = $this->renderer->render($factory());
        foreach ($needles as $needle) {
            $this->assertStringContainsString($needle, $html);
        }
    }
}
