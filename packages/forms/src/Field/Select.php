<?php

declare(strict_types=1);

namespace Serixa\Forms\Field;

use Serixa\Component\Select as FrameworkSelect;
use Serixa\Contracts\WidgetInterface;

/**
 * Select field composed around {@see FrameworkSelect}.
 */
final class Select extends Field
{
    /** @var array<string|int, string> */
    private array $options = [];

    private ?string $placeholder = null;

    private function __construct(string $name)
    {
        parent::__construct($name);
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    /**
     * @param array<string|int, string> $options
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    protected function renderControl(): WidgetInterface
    {
        $control = FrameworkSelect::make()->options($this->options);

        if ($this->placeholder !== null) {
            $control->placeholder($this->placeholder);
        }

        $stringValue = $this->stringValue($this->resolveValue());

        if ($stringValue !== null) {
            $control->value($stringValue);
        }

        /** @var FrameworkSelect $control */
        $control = $this->applyControlAttributes($control);

        return $control;
    }
}
