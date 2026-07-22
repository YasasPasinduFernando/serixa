# SERIXA Forms

`serixa/forms` adds validation, form state, binding, and field helpers on top of
framework HTML controls. It does **not** move functionality into the framework.

Depends only on `serixa/framework`. No admin, icons, ORM, routing, or JS framework.

## Validation

```php
use Serixa\Forms\Validation\RuleSet;
use Serixa\Forms\Validation\Validator;

$result = Validator::make($data, [
    'email' => 'required|email|max:255',
    'age' => RuleSet::make()->numeric()->between(18, 120),
    'password' => ['required', 'confirmed', 'min:8'],
    'website' => 'nullable|url',
])->validate();

if ($result->failed()) {
    $errors = $result->toErrorsBag();
}
```

### Built-in rules

required, email, url, min, max, between, confirmed, same, different, numeric,
integer, boolean, date, regex, in, not_in, accepted, nullable.

### Custom rules

```php
use Serixa\Forms\Validation\Rule;
use Serixa\Forms\Validation\RuleFactory;

RuleFactory::extend('uppercase', function (array $parameters): Rule {
    return new class implements Rule {
        public function passes(string $attribute, mixed $value, array $data): bool
        {
            return is_string($value) && $value === mb_strtoupper($value);
        }

        public function message(string $attribute, mixed $value, array $data): string
        {
            return 'The ' . str_replace('_', ' ', $attribute) . ' field must be uppercase.';
        }
    };
});
```

## Binding

```php
use Serixa\Forms\Binding\FormBinder;
use Serixa\Forms\Binding\RequestData;

$state = FormBinder::make()
    ->defaults(['timezone' => 'UTC'])
    ->bind($user)                     // array or object public props
    ->fill(RequestData::fromArray($_POST))
    ->oldInput()                      // prefer submitted values
    ->toFormState();
```

Merge order for values: **old > fill > bind > defaults**.

## Fluent fields

```php
use Serixa\Forms\Field\Input;

Input::make('email')
    ->label('Email')
    ->help('We never share your email.')
    ->required()
    ->email()
    ->max(255)
    ->autocomplete('email');
```

Fields compose `Serixa\Component\*` controls. They do not subclass them.

Use `formState(FormState $state)` to bind values/errors (named to avoid clashing
with framework widget `state()`).

## Form builder

```php
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Input;
use Serixa\Forms\Field\Textarea;

$form = FormBuilder::make()
    ->action('?page=contact')
    ->csrf($csrfProvider)   // optional; apps implement CsrfProvider
    ->state($formState)
    ->field(Input::make('email')->label('Email')->required()->email())
    ->field(Textarea::make('message')->label('Message')->required()->min(10))
    ->submit('Send')
    ->toWidget();
```

`$builder->rules()` returns `attribute => RuleSet` for `Validator::make()`.

## Sections

`FormSection`, `Fieldset` + `Legend`, `InlineFields`, `GridFields` group fields
with `forms.*` theme chrome.

## Errors

```php
use Serixa\Forms\Components\FieldError;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Components\WarningMessage;

ValidationSummary::make($errorsBag);
FieldError::make($message)->id('email-error');
SuccessMessage::make('Saved.');
WarningMessage::make('Check your input.');
```

Helpers (when `FormState::setCurrent()` was called):

```php
old('email');
errors('email');
hasError('email');
firstError('email');
```

## Accessibility

Fields automatically wire:

- stable ids from field names
- `aria-invalid` / `invalid()` on errors
- `aria-describedby` for help + error ids
- `required` / `aria-required` when the rule set includes required
- `role="alert"` on field errors and validation summaries
- label `for` matching control `id`

## Theme

`FormsTheme` decorates any `ThemeInterface` and resolves only `forms.*` keys:

- `forms.input`, `forms.error`, `forms.success`, `forms.warning`
- `forms.label`, `forms.help`, `forms.section`, `forms.section.title`, `forms.section.description`
- `forms.summary`, `forms.fieldset`, `forms.legend`, `forms.grid`, `forms.inline`, `forms.group`

No Tailwind strings in form widgets — only theme class arrays.

```php
ThemeManager::set(new FormsTheme(new DefaultTheme()));
```

Widgets also auto-wrap via `ResolvesFormsTheme` when needed.

## CSRF

```php
interface CsrfProvider
{
    public function token(): string;
    public function fieldName(): string;
    public function validate(?string $token): bool;
}
```

Applications implement this. The package ships no session/CSRF implementation.

## Examples

See `examples/login.php`, `profile.php`, `settings.php`, `contact.php`, `register.php`.
