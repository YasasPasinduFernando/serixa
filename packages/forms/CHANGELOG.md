# Changelog

All notable changes to `serixa/forms` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [0.1.0] - 2026-07-22

### Added

- Initial release of the official SERIXA Forms package.
- Validation: `Validator`, `ValidationResult`, `ValidationError`, `Rule`, `RuleSet`, `RuleFactory`, and built-in rules (required, email, url, min, max, between, confirmed, same, different, numeric, integer, boolean, date, regex, in, notIn, accepted, nullable).
- State: `FormState`, `FieldState`, `OldInput`, `ErrorsBag`, plus helpers `old()`, `errors()`, `hasError()`, `firstError()`.
- Binding: `FormBinder`, `RequestData`.
- Fluent fields composing framework controls: `Input`, `Textarea`, `Select`, `Checkbox`.
- `FormBuilder` with sections, groups, submit, and CSRF provider hook.
- Sections: `FormSection`, `Fieldset`, `Legend`, `InlineFields`, `GridFields`.
- Error/status widgets: `ValidationSummary`, `FieldError`, `SuccessMessage`, `WarningMessage`.
- `CsrfProvider` interface (applications implement).
- `FormsTheme` / `forms.*` theme keys.
- Examples: login, profile, settings, contact, register.
- Documentation in `docs/forms.md`.
