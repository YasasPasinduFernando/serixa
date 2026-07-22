<?php

declare(strict_types=1);

namespace Serixa\Forms\Field;

use Serixa\Component\Component;
use Serixa\Component\FieldGroup;
use Serixa\Component\HelperText;
use Serixa\Component\Label;
use Serixa\Contracts\WidgetInterface;
use Serixa\Forms\Components\FieldError;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\State\FormState;
use Serixa\Forms\Support\ResolvesFormsTheme;
use Serixa\Forms\Validation\Rule;
use Serixa\Forms\Validation\RuleSet;

/**
 * Abstract forms field that composes framework controls (does not subclass them).
 */
abstract class Field extends Component implements FormsWidgetInterface
{
    use ResolvesFormsTheme;

    protected string $name;

    protected ?string $labelText = null;

    protected ?string $helpText = null;

    protected ?string $idOverride = null;

    protected RuleSet $rules;

    protected ?string $autocomplete = null;

    protected ?FormState $state = null;

    protected function __construct(string $name)
    {
        parent::__construct();
        $this->name = $name;
        $this->rules = RuleSet::make();
    }

    public function label(string $label): static
    {
        $this->labelText = $label;

        return $this;
    }

    public function help(string $help): static
    {
        $this->helpText = $help;

        return $this;
    }

    /**
     * Override the field control DOM id (stable; derived from name by default).
     */
    public function id(string $id): static
    {
        $this->idOverride = $id;

        return $this;
    }

    /**
     * Bind this field to a {@see FormState} bag (values / errors).
     * Named {@see formState()} to avoid colliding with {@see Component}'s widget state helper.
     */
    public function formState(FormState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function autocomplete(string $autocomplete): static
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    public function rule(Rule $rule): static
    {
        $this->rules->rule($rule);

        return $this;
    }

    public function required(): static
    {
        $this->rules->required();

        return $this;
    }

    public function email(): static
    {
        $this->rules->email();

        return $this;
    }

    public function url(): static
    {
        $this->rules->url();

        return $this;
    }

    public function min(int|float $min): static
    {
        $this->rules->min($min);

        return $this;
    }

    public function max(int|float $max): static
    {
        $this->rules->max($max);

        return $this;
    }

    public function between(int|float $min, int|float $max): static
    {
        $this->rules->between($min, $max);

        return $this;
    }

    public function confirmed(): static
    {
        $this->rules->confirmed();

        return $this;
    }

    public function same(string $other): static
    {
        $this->rules->same($other);

        return $this;
    }

    public function different(string $other): static
    {
        $this->rules->different($other);

        return $this;
    }

    public function numeric(): static
    {
        $this->rules->numeric();

        return $this;
    }

    public function integer(): static
    {
        $this->rules->integer();

        return $this;
    }

    public function boolean(): static
    {
        $this->rules->boolean();

        return $this;
    }

    public function date(): static
    {
        $this->rules->date();

        return $this;
    }

    public function regex(string $pattern): static
    {
        $this->rules->regex($pattern);

        return $this;
    }

    /**
     * @param list<mixed> $values
     */
    public function in(array $values): static
    {
        $this->rules->in($values);

        return $this;
    }

    /**
     * @param list<mixed> $values
     */
    public function notIn(array $values): static
    {
        $this->rules->notIn($values);

        return $this;
    }

    public function accepted(): static
    {
        $this->rules->accepted();

        return $this;
    }

    public function nullable(): static
    {
        $this->rules->nullable();

        return $this;
    }

    public function ruleSet(): RuleSet
    {
        return $this->rules;
    }

    public function fieldName(): string
    {
        return $this->name;
    }

    public function fieldId(): string
    {
        if ($this->idOverride !== null) {
            return $this->idOverride;
        }

        return str_replace(['.', '[', ']'], ['-', '-', ''], $this->name);
    }

    abstract protected function renderControl(): WidgetInterface;

    public function render(): string
    {
        return $this->wrapField($this->renderControl());
    }

    protected function resolveValue(mixed $default = null): mixed
    {
        $state = $this->state ?? FormState::current();

        if ($state === null) {
            return $default;
        }

        return $state->value($this->name, $default);
    }

    /**
     * @return list<string>
     */
    protected function resolveErrors(): array
    {
        $state = $this->state ?? FormState::current();

        if ($state === null) {
            return [];
        }

        return $state->fieldErrors($this->name);
    }

    protected function isInvalid(): bool
    {
        return $this->resolveErrors() !== [];
    }

    protected function isRequired(): bool
    {
        return $this->rules->hasRequired();
    }

    protected function describedByIds(): ?string
    {
        $id = $this->fieldId();
        $parts = [];

        if ($this->helpText !== null) {
            $parts[] = $id . '-help';
        }

        if ($this->isInvalid()) {
            $parts[] = $id . '-error';
        }

        if ($parts === []) {
            return null;
        }

        return implode(' ', $parts);
    }

    /**
     * Whether to render a separate {@see Label} above the control.
     * Checkbox fields put the label on the control itself.
     */
    protected function shouldRenderExternalLabel(): bool
    {
        return $this->labelText !== null;
    }

    /**
     * Apply shared name/id/validation ARIA attributes onto a framework control.
     *
     * @template T of object
     * @param T $control
     * @return T
     */
    protected function applyControlAttributes(object $control): object
    {
        if (method_exists($control, 'name')) {
            $control->name($this->fieldName());
        }

        if (method_exists($control, 'id')) {
            $control->id($this->fieldId());
        }

        if ($this->isRequired() && method_exists($control, 'required')) {
            $control->required();
        }

        if ($this->isInvalid() && method_exists($control, 'invalid')) {
            $control->invalid();
        }

        $describedBy = $this->describedByIds();

        if ($describedBy !== null && method_exists($control, 'describedBy')) {
            $control->describedBy($describedBy);
        }

        if ($this->autocomplete !== null && method_exists($control, 'autocomplete')) {
            $control->autocomplete($this->autocomplete);
        }

        return $control;
    }

    protected function wrapField(WidgetInterface $control): string
    {
        $id = $this->fieldId();
        $children = [];

        if ($this->shouldRenderExternalLabel() && $this->labelText !== null) {
            $children[] = Label::make($this->labelText)->for($id);
        }

        $children[] = $control;

        if ($this->helpText !== null) {
            $children[] = HelperText::make($this->helpText)->id($id . '-help');
        }

        $errors = $this->resolveErrors();

        if ($errors !== []) {
            $children[] = FieldError::make($errors[0])->id($id . '-error');
        }

        $group = FieldGroup::make(...$children);

        $attrs = $this->buildAttributeString($this->theme()->classes('forms.input'));

        return sprintf('<div%s>%s</div>', $attrs, $group->render());
    }

    /**
     * Coerce a resolved field value to a string suitable for HTML controls.
     */
    protected function stringValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return null;
    }
}
