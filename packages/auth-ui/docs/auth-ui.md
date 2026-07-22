# SERIXA Auth UI

`serixa/auth-ui` ships authentication **screens and widgets**. It does not
authenticate users.

## Architecture

| Layer | Responsibility |
|-------|----------------|
| Framework | Generic HTML controls, Button, Document |
| forms | Validation, FormBuilder, field helpers |
| icons | Inline SVG icons |
| admin | Theme decorator only (`admin.*`) |
| **auth-ui** | Auth layouts + cards |
| Your app | Sessions, guards, password hashing, OAuth, DB |

Theme stack (outer → inner):

`AuthTheme → FormsTheme → AdminTheme → DefaultTheme`

## Public API

```php
AuthLayout::make()
AuthCard::make()
LoginCard::make()
RegisterCard::make()
ForgotPasswordCard::make()
ResetPasswordCard::make()
VerifyEmailCard::make()
MagicLinkCard::make()
TwoFactorCard::make()
SocialLoginButtons::make()
PasswordStrength::make()
PasswordRequirements::make()
RememberMeCheckbox::make()
BrandLogo::make()
```

## Wiring real auth

```php
LoginCard::make()
    ->action('/login')           // your route
    ->csrf($yourCsrfProvider)    // Serixa\Forms\Contracts\CsrfProvider
    ->formState($formState);     // old input + errors from serixa/forms
```

- **Laravel:** POST to `Route::post('/login', ...)` / Fortify / Breeze controller.
- **Symfony:** POST to a security firewall form login path.
- **Slim / custom:** POST to your handler; set cookies/sessions yourself.
- **API:** POST JSON from a thin controller; still render these cards SSR-first.

Social buttons are **links** (`href`) you set — the package never talks to Google/GitHub.

## Accessibility

Forms fields from `serixa/forms` provide labels, `autocomplete`, `aria-invalid`,
`aria-describedby`, and `role="alert"` on errors. Password strength uses
`aria-live="polite"`. Requirements use `role="list"`.

## Examples

Run from the package directory after `composer install`:

```bash
php examples/login.php
php examples/register.php
php examples/forgot-password.php
php examples/reset-password.php
php examples/two-factor.php
php examples/magic-link.php
```
