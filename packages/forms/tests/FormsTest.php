<?php

declare(strict_types=1);

namespace Serixa\Forms\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Forms\Binding\FormBinder;
use Serixa\Forms\Binding\RequestData;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\FieldError;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Components\WarningMessage;
use Serixa\Forms\Contracts\CsrfProvider;
use Serixa\Forms\Field\Checkbox;
use Serixa\Forms\Field\Input;
use Serixa\Forms\Field\Select;
use Serixa\Forms\Field\Textarea;
use Serixa\Forms\Section\Fieldset;
use Serixa\Forms\Section\FormSection;
use Serixa\Forms\Section\GridFields;
use Serixa\Forms\Section\InlineFields;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\FormState;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Theme\FormsTheme;
use Serixa\Forms\Validation\Rule;
use Serixa\Forms\Validation\RuleFactory;
use Serixa\Forms\Validation\RuleSet;
use Serixa\Forms\Validation\Validator;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class FormsTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        FormState::clearCurrent();
        $this->renderer = new Renderer();
    }

    protected function tearDown(): void
    {
        FormState::clearCurrent();
        ThemeManager::reset();
    }

    public function testRequiredRuleFailsOnEmpty(): void
    {
        $result = Validator::make(['email' => ''], ['email' => 'required'])->validate();
        $this->assertTrue($result->failed());
        $this->assertCount(1, $result->errors());
        $this->assertStringContainsString('required', strtolower($result->errors()[0]->message()));
    }

    public function testRequiredRulePassesWithValue(): void
    {
        $result = Validator::make(['email' => 'a@b.co'], ['email' => 'required'])->validate();
        $this->assertTrue($result->passed());
        $this->assertTrue($result->ok());
    }

    public function testEmailRule(): void
    {
        $this->assertTrue(Validator::make(['e' => 'bad'], ['e' => 'email'])->validate()->failed());
        $this->assertTrue(Validator::make(['e' => 'good@example.com'], ['e' => 'email'])->validate()->passed());
    }

    public function testUrlRule(): void
    {
        $this->assertTrue(Validator::make(['u' => 'not-a-url'], ['u' => 'url'])->validate()->failed());
        $this->assertTrue(Validator::make(['u' => 'https://serixa.dev'], ['u' => 'url'])->validate()->passed());
    }

    public function testMinMaxStringLength(): void
    {
        $this->assertTrue(Validator::make(['n' => 'ab'], ['n' => 'min:3'])->validate()->failed());
        $this->assertTrue(Validator::make(['n' => 'abcd'], ['n' => 'min:3'])->validate()->passed());
        $this->assertTrue(Validator::make(['n' => 'abcdef'], ['n' => 'max:5'])->validate()->failed());
        $this->assertTrue(Validator::make(['n' => 'abc'], ['n' => 'max:5'])->validate()->passed());
    }

    public function testBetweenNumeric(): void
    {
        $this->assertTrue(Validator::make(['a' => 5], ['a' => 'numeric|between:10,20'])->validate()->failed());
        $this->assertTrue(Validator::make(['a' => 15], ['a' => 'numeric|between:10,20'])->validate()->passed());
    }

    public function testConfirmedSameDifferent(): void
    {
        $this->assertTrue(Validator::make([
            'password' => 'secret',
            'password_confirmation' => 'nope',
        ], ['password' => 'confirmed'])->validate()->failed());

        $this->assertTrue(Validator::make([
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ], ['password' => 'confirmed'])->validate()->passed());

        $this->assertTrue(Validator::make([
            'a' => 'x',
            'b' => 'y',
        ], ['a' => 'same:b'])->validate()->failed());

        $this->assertTrue(Validator::make([
            'a' => 'x',
            'b' => 'x',
        ], ['a' => 'same:b'])->validate()->passed());

        $this->assertTrue(Validator::make([
            'a' => 'x',
            'b' => 'x',
        ], ['a' => 'different:b'])->validate()->failed());

        $this->assertTrue(Validator::make([
            'a' => 'x',
            'b' => 'y',
        ], ['a' => 'different:b'])->validate()->passed());
    }

    public function testNumericIntegerBooleanDate(): void
    {
        $this->assertTrue(Validator::make(['n' => 'abc'], ['n' => 'numeric'])->validate()->failed());
        $this->assertTrue(Validator::make(['n' => '12.5'], ['n' => 'numeric'])->validate()->passed());
        $this->assertTrue(Validator::make(['n' => '12.5'], ['n' => 'integer'])->validate()->failed());
        $this->assertTrue(Validator::make(['n' => '12'], ['n' => 'integer'])->validate()->passed());
        $this->assertTrue(Validator::make(['b' => 'maybe'], ['b' => 'boolean'])->validate()->failed());
        $this->assertTrue(Validator::make(['b' => '1'], ['b' => 'boolean'])->validate()->passed());
        $this->assertTrue(Validator::make(['d' => 'not-a-date'], ['d' => 'date'])->validate()->failed());
        $this->assertTrue(Validator::make(['d' => '2026-07-22'], ['d' => 'date'])->validate()->passed());
    }

    public function testRegexInNotInAccepted(): void
    {
        $this->assertTrue(Validator::make(['c' => 'ab'], ['c' => 'regex:/^[a-z]{3}$/'])->validate()->failed());
        $this->assertTrue(Validator::make(['c' => 'abc'], ['c' => 'regex:/^[a-z]{3}$/'])->validate()->passed());
        $this->assertTrue(Validator::make(['r' => 'guest'], ['r' => 'in:admin,editor'])->validate()->failed());
        $this->assertTrue(Validator::make(['r' => 'admin'], ['r' => 'in:admin,editor'])->validate()->passed());
        $this->assertTrue(Validator::make(['r' => 'banned'], ['r' => 'not_in:banned,blocked'])->validate()->failed());
        $this->assertTrue(Validator::make(['r' => 'ok'], ['r' => 'not_in:banned,blocked'])->validate()->passed());
        $this->assertTrue(Validator::make(['t' => 'no'], ['t' => 'accepted'])->validate()->failed());
        $this->assertTrue(Validator::make(['t' => 'yes'], ['t' => 'accepted'])->validate()->passed());
    }

    public function testNullableSkipsOtherRulesWhenEmpty(): void
    {
        $result = Validator::make(['website' => ''], ['website' => 'nullable|url'])->validate();
        $this->assertTrue($result->passed());
    }

    public function testNullableStillValidatesWhenPresent(): void
    {
        $result = Validator::make(['website' => 'bad'], ['website' => 'nullable|url'])->validate();
        $this->assertTrue($result->failed());
    }

    public function testRuleSetFluentApi(): void
    {
        $set = RuleSet::make()->required()->email()->max(255);
        $this->assertTrue($set->hasRequired());
        $this->assertCount(3, $set->rules());

        $result = Validator::make(
            ['email' => 'ada@serixa.dev'],
            ['email' => $set],
        )->validate();

        $this->assertTrue($result->passed());
    }

    public function testCustomRuleExtension(): void
    {
        RuleFactory::extend('uppercase', static function (array $parameters): Rule {
            return new class () implements Rule {
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

        $this->assertTrue(Validator::make(['code' => 'abc'], ['code' => 'uppercase'])->validate()->failed());
        $this->assertTrue(Validator::make(['code' => 'ABC'], ['code' => 'uppercase'])->validate()->passed());
    }

    public function testErrorsBagAndValidationResultConversion(): void
    {
        $result = Validator::make(['email' => ''], ['email' => 'required|email'])->validate();
        $bag = $result->toErrorsBag();

        $this->assertTrue($bag->has('email'));
        $this->assertTrue($bag->any());
        $this->assertFalse($bag->isEmpty());
        $this->assertNotNull($bag->first('email'));
        $this->assertNotEmpty($bag->get('email'));
        $this->assertArrayHasKey('email', $bag->all());

        $state = $result->toFormState();
        $this->assertTrue($state->hasError('email'));
        $this->assertNotNull($state->firstError('email'));
    }

    public function testFormStateValueResolutionOrder(): void
    {
        $state = FormState::make()
            ->withDefaults(['name' => 'default'])
            ->withValues(['name' => 'bound'])
            ->withOldInput(OldInput::make(['name' => 'old']));

        $this->assertSame('old', $state->value('name'));
        $this->assertSame('fallback', $state->value('missing', 'fallback'));
    }

    public function testFormBinderMergeOrder(): void
    {
        $state = FormBinder::make()
            ->defaults(['a' => '1', 'b' => '1', 'c' => '1', 'd' => '1'])
            ->bind(['b' => '2', 'c' => '2', 'd' => '2'])
            ->fill(RequestData::fromArray(['c' => '3', 'd' => '3']))
            ->oldInput(OldInput::make(['d' => '4']))
            ->toFormState();

        $this->assertSame('1', $state->value('a'));
        $this->assertSame('2', $state->value('b'));
        $this->assertSame('3', $state->value('c'));
        $this->assertSame('4', $state->value('d'));
    }

    public function testFormBinderBindObject(): void
    {
        $user = new class () {
            public string $email = 'ada@serixa.dev';
            public string $name = 'Ada';
        };

        $state = FormBinder::make()->bind($user)->toFormState();
        $this->assertSame('Ada', $state->value('name'));
        $this->assertSame('ada@serixa.dev', $state->value('email'));
    }

    public function testRequestDataHelpers(): void
    {
        $data = RequestData::fromArray(['flag' => '1', 'title' => 'Hello', 'x' => 'y']);
        $this->assertTrue($data->has('title'));
        $this->assertSame('Hello', $data->string('title'));
        $this->assertTrue($data->boolean('flag'));
        $this->assertSame(['title' => 'Hello'], $data->only('title'));
        $this->assertArrayHasKey('x', $data->all());
    }

    public function testHelperFunctionsUseCurrentFormState(): void
    {
        $state = FormState::make()
            ->withOldInput(OldInput::make(['name' => 'Ada']))
            ->withErrors(ErrorsBag::make(['email' => ['The email field is required.']]));
        $state->setCurrent();

        $this->assertSame('Ada', old('name'));
        $this->assertTrue(hasError('email'));
        $this->assertSame('The email field is required.', firstError('email'));
        $this->assertSame(['The email field is required.'], errors('email'));
    }

    public function testInputFieldRendersAccessibilityAttributesOnError(): void
    {
        $state = FormState::make()
            ->withOldInput(OldInput::make(['email' => 'bad']))
            ->withErrors(ErrorsBag::make(['email' => ['The email field must be a valid email address.']]));

        $html = $this->renderer->renderWidget(
            Input::make('email')->label('Email')->required()->email()->formState($state),
        );

        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('id="email"', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('aria-required="true"', $html);
        $this->assertStringContainsString('aria-describedby="email-error"', $html);
        $this->assertStringContainsString('id="email-error"', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('value="bad"', $html);
        $this->assertStringContainsString('for="email"', $html);
    }

    public function testInputFieldWiresHelpDescribedBy(): void
    {
        $html = $this->renderer->renderWidget(
            Input::make('bio')->label('Bio')->help('Keep it short.')->max(120),
        );

        $this->assertStringContainsString('id="bio-help"', $html);
        $this->assertStringContainsString('aria-describedby="bio-help"', $html);
        $this->assertStringContainsString('Keep it short.', $html);
    }

    public function testTextareaSelectCheckboxRender(): void
    {
        $state = FormState::make()->withValues([
            'about' => 'Hello world',
            'role' => 'admin',
            'terms' => '1',
        ]);

        $textarea = $this->renderer->renderWidget(
            Textarea::make('about')->label('About')->formState($state)->rows(4),
        );
        $this->assertStringContainsString('<textarea', $textarea);
        $this->assertStringContainsString('Hello world', $textarea);

        $select = $this->renderer->renderWidget(
            Select::make('role')->label('Role')->options(['admin' => 'Admin', 'editor' => 'Editor'])->formState($state),
        );
        $this->assertStringContainsString('<select', $select);
        $this->assertStringContainsString('selected', $select);

        $checkbox = $this->renderer->renderWidget(
            Checkbox::make('terms')->label('Accept terms')->accepted()->formState($state),
        );
        $this->assertStringContainsString('type="checkbox"', $checkbox);
        $this->assertStringContainsString('checked', $checkbox);
    }

    public function testFormBuilderWithCsrfAndSubmit(): void
    {
        $csrf = new class () implements CsrfProvider {
            public function token(): string
            {
                return 'demo-token';
            }

            public function fieldName(): string
            {
                return '_token';
            }

            public function validate(?string $token): bool
            {
                return $token === 'demo-token';
            }
        };

        $builder = FormBuilder::make()
            ->action('?page=profile')
            ->csrf($csrf)
            ->novalidate()
            ->field(Input::make('email')->label('Email')->required()->email())
            ->submit('Save');

        $html = $builder->render();

        $this->assertStringContainsString('<form', $html);
        $this->assertStringContainsString('method="POST"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringContainsString('value="demo-token"', $html);
        $this->assertStringContainsString('novalidate', $html);
        $this->assertStringContainsString('Save', $html);
        $this->assertArrayHasKey('email', $builder->rules());
        $this->assertTrue($builder->rules()['email']->hasRequired());
    }

    public function testValidationSummaryAndMessages(): void
    {
        $bag = ErrorsBag::make([
            'email' => ['Email is required.'],
            'name' => ['Name is required.'],
        ]);

        $summary = $this->renderer->renderWidget(ValidationSummary::make($bag));
        $this->assertStringContainsString('role="alert"', $summary);
        $this->assertStringContainsString('Email is required.', $summary);
        $this->assertStringContainsString('Name is required.', $summary);

        $success = $this->renderer->renderWidget(SuccessMessage::make('Saved.'));
        $this->assertStringContainsString('Saved.', $success);

        $warning = $this->renderer->renderWidget(WarningMessage::make('Careful.'));
        $this->assertStringContainsString('Careful.', $warning);

        $fieldError = $this->renderer->renderWidget(FieldError::make('Oops')->id('x-error'));
        $this->assertStringContainsString('id="x-error"', $fieldError);
        $this->assertStringContainsString('role="alert"', $fieldError);
    }

    public function testSectionsRender(): void
    {
        $section = $this->renderer->renderWidget(
            FormSection::make('Account')->description('Your details.')->child(
                Input::make('name')->label('Name'),
            ),
        );
        $this->assertStringContainsString('Account', $section);
        $this->assertStringContainsString('Your details.', $section);

        $fieldset = $this->renderer->renderWidget(
            Fieldset::make(Input::make('password')->type('password')->label('Password'))->legend('Security'),
        );
        $this->assertStringContainsString('<fieldset', $fieldset);
        $this->assertStringContainsString('<legend', $fieldset);
        $this->assertStringContainsString('Security', $fieldset);

        $inline = $this->renderer->renderWidget(InlineFields::make(Input::make('a'), Input::make('b')));
        $this->assertStringContainsString('name="a"', $inline);

        $grid = $this->renderer->renderWidget(GridFields::make(Input::make('c'), Input::make('d')));
        $this->assertStringContainsString('name="c"', $grid);
    }

    public function testFormsThemeResolvesKeys(): void
    {
        $theme = new FormsTheme(new DefaultTheme());

        foreach ([
            'forms.input',
            'forms.error',
            'forms.success',
            'forms.label',
            'forms.help',
            'forms.section',
            'forms.summary',
            'forms.fieldset',
            'forms.grid',
            'forms.inline',
        ] as $key) {
            $classes = $theme->classes($key);
            $this->assertNotEmpty($classes, $key . ' should resolve classes');
        }

        $this->assertNotEmpty($theme->classes('input'));
        $this->assertSame($theme->inner()->buttonPrimary(), $theme->buttonPrimary());
    }

    public function testFluentFieldCollectsRulesForValidator(): void
    {
        $email = Input::make('email')->required()->email()->max(255);
        $result = Validator::make(
            ['email' => 'not-an-email'],
            ['email' => $email->ruleSet()],
        )->validate();

        $this->assertTrue($result->failed());
        $this->assertTrue($result->toErrorsBag()->has('email'));
    }
}
