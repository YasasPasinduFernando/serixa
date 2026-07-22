<?php

declare(strict_types=1);

namespace Serixa\Forms\Components;

use Serixa\Component\Component;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\Support\ResolvesFormsTheme;

/**
 * Field-level validation error message with {@code role=alert}.
 */
final class FieldError extends Component implements FormsWidgetInterface
{
    use ResolvesFormsTheme;

    private string $message;

    private function __construct(string $message)
    {
        parent::__construct();
        $this->message = $message;
    }

    public static function make(string $message): self
    {
        return new self($message);
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('forms.error'),
            ['role' => 'alert'],
        );

        return sprintf('<p%s>%s</p>', $attrs, $this->escape($this->message));
    }
}
