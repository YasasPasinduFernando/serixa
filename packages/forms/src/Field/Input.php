<?php

declare(strict_types=1);

namespace Serixa\Forms\Field;

use Serixa\Component\Input as FrameworkInput;
use Serixa\Contracts\WidgetInterface;

/**
 * Textual input field composed around {@see FrameworkInput}.
 */
final class Input extends Field
{
    private string $type = 'text';

    private ?string $placeholder = null;

    private mixed $explicitValue = null;

    private bool $hasExplicitValue = false;

    private function __construct(string $name)
    {
        parent::__construct($name);
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function type(string $type = 'text'): self
    {
        $this->type = $type;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Explicit value override (takes precedence over form state).
     */
    public function value(mixed $value): self
    {
        $this->explicitValue = $value;
        $this->hasExplicitValue = true;

        return $this;
    }

    protected function renderControl(): WidgetInterface
    {
        $control = FrameworkInput::make()->type($this->type);

        if ($this->placeholder !== null) {
            $control->placeholder($this->placeholder);
        }

        $raw = $this->hasExplicitValue ? $this->explicitValue : $this->resolveValue();
        $stringValue = $this->stringValue($raw);

        if ($stringValue !== null) {
            $control->value($stringValue);
        }

        /** @var FrameworkInput $control */
        $control = $this->applyControlAttributes($control);

        return $control;
    }
}
