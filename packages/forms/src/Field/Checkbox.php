<?php

declare(strict_types=1);

namespace Serixa\Forms\Field;

use Serixa\Component\Checkbox as FrameworkCheckbox;
use Serixa\Contracts\WidgetInterface;

/**
 * Checkbox field composed around {@see FrameworkCheckbox}.
 *
 * The field label is rendered on the checkbox control itself (not a separate Label).
 */
final class Checkbox extends Field
{
    private string $checkboxValue = '1';

    private function __construct(string $name)
    {
        parent::__construct($name);
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    /**
     * Value attribute for the checkbox input (default {@code 1}).
     */
    public function value(string $value): self
    {
        $this->checkboxValue = $value;

        return $this;
    }

    protected function shouldRenderExternalLabel(): bool
    {
        return false;
    }

    protected function renderControl(): WidgetInterface
    {
        $control = FrameworkCheckbox::make()->value($this->checkboxValue);

        if ($this->labelText !== null) {
            $control->label($this->labelText);
        }

        $resolved = $this->resolveValue();
        $checked = $this->isTruthy($resolved);
        $control->checked($checked);

        /** @var FrameworkCheckbox $control */
        $control = $this->applyControlAttributes($control);

        return $control;
    }

    private function isTruthy(mixed $value): bool
    {
        if ($value === null || $value === false || $value === '' || $value === 0 || $value === '0') {
            return false;
        }

        return true;
    }
}
