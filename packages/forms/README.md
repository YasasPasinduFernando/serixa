# SERIXA Forms

Official forms package for SERIXA — validation, state, binding, and field helpers on top of framework HTML controls.

Framework stays generic. `serixa/forms` owns everything beyond basic controls.

## Requirements

- PHP 8.2+
- `serixa/framework`

## Installation (monorepo)

```bash
cd packages/forms
composer install
composer qa
```

## Quick start

```php
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Input;
use Serixa\Forms\Validation\Validator;

$builder = FormBuilder::make()
    ->action('/profile')
    ->field(Input::make('email')->label('Email')->required()->email()->max(255))
    ->submit('Save');

$result = Validator::make($_POST, $builder->rules())->validate();
```

See [docs/forms.md](docs/forms.md) for the full guide.
