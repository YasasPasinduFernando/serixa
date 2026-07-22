<?php

declare(strict_types=1);

namespace Serixa\Forms\Builder;

use Serixa\Component\Button;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form as FrameworkForm;
use Serixa\Component\Input as FrameworkInput;
use Serixa\Contracts\WidgetInterface;
use Serixa\Forms\Contracts\CsrfProvider;
use Serixa\Forms\Field\Field;
use Serixa\Forms\Section\FormSection;
use Serixa\Forms\State\FormState;
use Serixa\Forms\Validation\RuleSet;

/**
 * Fluent builder that assembles a {@see FrameworkForm} from fields and layout widgets.
 */
final class FormBuilder
{
    private string $method = 'POST';

    private ?string $action = null;

    private ?FormState $state = null;

    private ?CsrfProvider $csrf = null;

    private bool $novalidate = false;

    /** @var list<WidgetInterface|string> */
    private array $children = [];

    /** @var list<Field> */
    private array $fields = [];

    private function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    public function method(string $method): self
    {
        $this->method = strtoupper($method);

        return $this;
    }

    public function action(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function state(FormState $state): self
    {
        $this->state = $state;

        foreach ($this->fields as $field) {
            $field->formState($state);
        }

        return $this;
    }

    public function csrf(CsrfProvider $csrf): self
    {
        $this->csrf = $csrf;

        return $this;
    }

    public function novalidate(bool $novalidate = true): self
    {
        $this->novalidate = $novalidate;

        return $this;
    }

    public function field(Field $field): self
    {
        if ($this->state !== null) {
            $field->formState($this->state);
        }

        $this->fields[] = $field;
        $this->children[] = $field;

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public function group(WidgetInterface|string|array ...$children): self
    {
        $normalized = $children;

        if (count($normalized) === 1 && is_array($normalized[0])) {
            $normalized = $normalized[0];
        }

        foreach ($normalized as $child) {
            if ($child instanceof Field) {
                if ($this->state !== null) {
                    $child->formState($this->state);
                }

                $this->fields[] = $child;
            }
        }

        $this->children[] = FieldGroup::make(...$children);

        return $this;
    }

    /**
     * Add a {@see FormSection}, or build one from a title plus widgets / a callback.
     *
     * @param FormSection|string $sectionOrTitle
     * @param WidgetInterface|string|callable(FormSection):void ...$rest
     */
    public function section(FormSection|string $sectionOrTitle, mixed ...$rest): self
    {
        if ($sectionOrTitle instanceof FormSection) {
            $this->children[] = $sectionOrTitle;

            return $this;
        }

        $section = FormSection::make($sectionOrTitle);

        foreach ($rest as $item) {
            if (is_callable($item)) {
                $item($section);

                continue;
            }

            if ($item instanceof WidgetInterface || is_string($item)) {
                $section->child($item);

                if ($item instanceof Field) {
                    if ($this->state !== null) {
                        $item->formState($this->state);
                    }

                    $this->fields[] = $item;
                }
            }
        }

        $this->children[] = $section;

        return $this;
    }

    public function submit(string $label = 'Submit'): self
    {
        $this->children[] = Button::make($label)->type('submit')->primary();

        return $this;
    }

    /**
     * Append an arbitrary widget or HTML string.
     */
    public function add(WidgetInterface|string $child): self
    {
        if ($child instanceof Field) {
            return $this->field($child);
        }

        $this->children[] = $child;

        return $this;
    }

    /**
     * @return array<string, RuleSet>
     */
    public function rules(): array
    {
        $rules = [];

        foreach ($this->fields as $field) {
            $rules[$field->fieldName()] = $field->ruleSet();
        }

        return $rules;
    }

    public function toWidget(): FrameworkForm
    {
        $children = [];

        if ($this->csrf !== null) {
            $children[] = FrameworkInput::make()
                ->type('hidden')
                ->name($this->csrf->fieldName())
                ->value($this->csrf->token());
        }

        foreach ($this->children as $child) {
            $children[] = $child;
        }

        $form = FrameworkForm::make(...$children)->method($this->method);

        if ($this->action !== null) {
            $form->action($this->action);
        }

        if ($this->novalidate) {
            $form->novalidate(true);
        }

        return $form;
    }

    public function render(): string
    {
        return $this->toWidget()->render();
    }
}
