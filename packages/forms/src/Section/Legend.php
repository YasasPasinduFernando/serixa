<?php

declare(strict_types=1);

namespace Serixa\Forms\Section;

use Serixa\Component\Component;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\Support\ResolvesFormsTheme;

/**
 * Fieldset legend.
 */
final class Legend extends Component implements FormsWidgetInterface
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
        $attrs = $this->buildAttributeString($this->theme()->classes('forms.legend'));

        return sprintf('<legend%s>%s</legend>', $attrs, $this->escape($this->text));
    }
}
