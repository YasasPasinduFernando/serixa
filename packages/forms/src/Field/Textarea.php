<?php

declare(strict_types=1);

namespace Serixa\Forms\Field;

use Serixa\Component\Textarea as FrameworkTextarea;
use Serixa\Contracts\WidgetInterface;

/**
 * Multi-line text field composed around {@see FrameworkTextarea}.
 */
final class Textarea extends Field
{
    private ?string $placeholder = null;

    private int $rows = 3;

    private ?int $cols = null;

    private function __construct(string $name)
    {
        parent::__construct($name);
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function rows(int $rows): self
    {
        $this->rows = max(1, $rows);

        return $this;
    }

    public function cols(int $cols): self
    {
        $this->cols = max(1, $cols);

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    protected function renderControl(): WidgetInterface
    {
        $content = $this->stringValue($this->resolveValue()) ?? '';

        $control = FrameworkTextarea::make($content)->rows($this->rows);

        if ($this->cols !== null) {
            $control->cols($this->cols);
        }

        if ($this->placeholder !== null) {
            $control->placeholder($this->placeholder);
        }

        /** @var FrameworkTextarea $control */
        $control = $this->applyControlAttributes($control);

        return $control;
    }
}
