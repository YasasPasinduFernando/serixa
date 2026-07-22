<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\AuthUi\Components\AuthDivider;
use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Components\PasswordRequirements;
use Serixa\AuthUi\Components\PasswordStrength;
use Serixa\AuthUi\Components\RememberMeCheckbox;
use Serixa\AuthUi\Components\SocialLoginButtons;
use Serixa\AuthUi\Layout\AuthAside;
use Serixa\AuthUi\Layout\AuthCard;
use Serixa\AuthUi\Layout\AuthFooter;
use Serixa\AuthUi\Layout\AuthHeader;
use Serixa\AuthUi\Layout\AuthIllustration;
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\ForgotPasswordCard;
use Serixa\AuthUi\Pages\LoginCard;
use Serixa\AuthUi\Pages\MagicLinkCard;
use Serixa\AuthUi\Pages\RegisterCard;
use Serixa\AuthUi\Pages\ResetPasswordCard;
use Serixa\AuthUi\Pages\TwoFactorCard;
use Serixa\AuthUi\Pages\VerifyEmailCard;
use Serixa\AuthUi\Theme\AuthTheme;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\FormState;
use Serixa\Forms\State\OldInput;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class AuthUiTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        FormState::clearCurrent();
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        FormState::clearCurrent();
    }

    public function testLoginCardRendersFormAndAccessibility(): void
    {
        $html = $this->renderer->renderWidget(
            LoginCard::make()->action('/login')->brand('Acme'),
        );

        $this->assertStringContainsString('<form', $html);
        $this->assertStringContainsString('action="/login"', $html);
        $this->assertStringContainsString('method="POST"', $html);
        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('name="password"', $html);
        $this->assertStringContainsString('autocomplete="email"', $html);
        $this->assertStringContainsString('autocomplete="current-password"', $html);
        $this->assertStringContainsString('Sign in', $html);
        $this->assertStringContainsString('name="remember"', $html);
        $this->assertStringContainsString('Create an account', $html);
        $this->assertStringContainsString('Forgot password?', $html);
    }

    public function testLoginCardShowsValidationSummaryAndAriaInvalid(): void
    {
        $state = FormState::make()
            ->withOldInput(OldInput::make(['email' => 'bad']))
            ->withErrors(ErrorsBag::make(['email' => ['The email field must be a valid email address.']]));

        $html = $this->renderer->renderWidget(
            LoginCard::make()->action('/login')->formState($state)->showSummary(),
        );

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('The email field must be a valid email address.', $html);
        $this->assertStringContainsString('value="bad"', $html);
    }

    public function testRegisterCardRendersFields(): void
    {
        $html = $this->renderer->renderWidget(RegisterCard::make()->action('/register'));

        $this->assertStringContainsString('name="name"', $html);
        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('name="password"', $html);
        $this->assertStringContainsString('name="password_confirmation"', $html);
        $this->assertStringContainsString('autocomplete="new-password"', $html);
        $this->assertStringContainsString('Create account', $html);
    }

    public function testForgotAndResetCardsRender(): void
    {
        $forgot = $this->renderer->renderWidget(ForgotPasswordCard::make()->action('/forgot'));
        $this->assertStringContainsString('name="email"', $forgot);
        $this->assertStringContainsString('Send reset link', $forgot);

        $reset = $this->renderer->renderWidget(ResetPasswordCard::make()->action('/reset'));
        $this->assertStringContainsString('name="password"', $reset);
        $this->assertStringContainsString('name="password_confirmation"', $reset);
        $this->assertStringContainsString('Reset password', $reset);
    }

    public function testVerifyTwoFactorMagicCardsRender(): void
    {
        $verify = $this->renderer->renderWidget(VerifyEmailCard::make()->resendHref('/resend'));
        $this->assertStringContainsString('Resend', $verify);

        $twoFactor = $this->renderer->renderWidget(TwoFactorCard::make()->action('/2fa'));
        $this->assertStringContainsString('autocomplete="one-time-code"', $twoFactor);
        $this->assertStringContainsString('Verify', $twoFactor);

        $magic = $this->renderer->renderWidget(MagicLinkCard::make()->action('/magic'));
        $this->assertStringContainsString('name="email"', $magic);
        $this->assertStringContainsString('magic', strtolower($magic));
    }

    public function testAuthLayoutCenteredAndSplit(): void
    {
        $centered = $this->renderer->renderWidget(
            AuthLayout::make()
                ->brand(BrandLogo::make('SERIXA'))
                ->card(LoginCard::make()->action('/login'))
                ->footer(AuthFooter::make('© 2026')),
        );
        $this->assertStringContainsString('SERIXA', $centered);
        $this->assertStringContainsString('Sign in', $centered);
        $this->assertStringContainsString('© 2026', $centered);

        $split = $this->renderer->renderWidget(
            AuthLayout::make()
                ->aside(AuthAside::make('Welcome back'))
                ->illustration(AuthIllustration::make('Secure by design'))
                ->content(LoginCard::make()->action('/login')),
        );
        $this->assertStringContainsString('Welcome back', $split);
        $this->assertStringContainsString('Secure by design', $split);
    }

    public function testAuthCardHeaderFooter(): void
    {
        $html = $this->renderer->renderWidget(
            AuthCard::make(
                AuthHeader::make('Hello'),
                'Body',
                AuthFooter::make('Footer'),
            ),
        );

        $this->assertStringContainsString('Hello', $html);
        $this->assertStringContainsString('Body', $html);
        $this->assertStringContainsString('Footer', $html);
    }

    public function testSocialDividerRememberComponents(): void
    {
        $social = $this->renderer->renderWidget(
            SocialLoginButtons::make()
                ->provider('GitHub', '/oauth/github', 'code-bracket')
                ->provider('Google', '/oauth/google', 'at-symbol'),
        );
        $this->assertStringContainsString('GitHub', $social);
        $this->assertStringContainsString('href="/oauth/github"', $social);
        $this->assertStringContainsString('<svg', $social);

        $divider = $this->renderer->renderWidget(AuthDivider::make('or continue with'));
        $this->assertStringContainsString('or continue with', $divider);

        $remember = $this->renderer->renderWidget(RememberMeCheckbox::make());
        $this->assertStringContainsString('name="remember"', $remember);
        $this->assertStringContainsString('type="checkbox"', $remember);
    }

    public function testPasswordStrengthAndRequirements(): void
    {
        $empty = $this->renderer->renderWidget(PasswordStrength::make(''));
        $this->assertStringContainsString('aria-live="polite"', $empty);

        $strong = $this->renderer->renderWidget(PasswordStrength::make('abcdefghijklmnopqrst'));
        $this->assertStringContainsString('Strong', $strong);

        $reqs = $this->renderer->renderWidget(
            PasswordRequirements::make()
                ->requirement('At least 12 characters', true)
                ->requirement('One number', false),
        );
        $this->assertStringContainsString('role="list"', $reqs);
        $this->assertStringContainsString('At least 12 characters', $reqs);
        $this->assertStringContainsString('<svg', $reqs);
    }

    public function testAuthThemeResolvesKeys(): void
    {
        $theme = new AuthTheme(new DefaultTheme());

        foreach ([
            'auth.layout',
            'auth.card',
            'auth.header',
            'auth.footer',
            'auth.aside',
            'auth.illustration',
            'auth.brand',
            'auth.divider',
            'auth.social',
            'auth.strength',
            'auth.requirements',
            'auth.title',
            'auth.subtitle',
            'auth.link',
            'auth.alert',
        ] as $key) {
            $this->assertNotEmpty($theme->classes($key), $key);
        }

        $this->assertNotEmpty($theme->classes('input'));
        $this->assertSame($theme->inner()->buttonPrimary(), $theme->buttonPrimary());
    }

    public function testBrandLogoRenders(): void
    {
        $html = $this->renderer->renderWidget(BrandLogo::make('Acme Auth')->href('/'));
        $this->assertStringContainsString('Acme Auth', $html);
        $this->assertStringContainsString('href="/"', $html);
    }
}
