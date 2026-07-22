<?php

declare(strict_types=1);

namespace Serixa\Forms\Components;

use Serixa\Component\Component;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\Support\ResolvesFormsTheme;

/**
 * Warning message with {@code role=alert}.
 */
final class WarningMessage extends Component implements FormsWidgetInterface
{
    use ResolvesFormsTheme;

    private string $text;

    private function __construct(string $text)
    {
        parent::__construct();
        $this->text = $text;
    }

    public static function make(string $text): self
    {
        return new self($text);
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('forms.warning'),
            ['role' => 'alert'],
        );

        return sprintf('<p%s>%s</p>', $attrs, $this->escape($this->text));
    }
}
