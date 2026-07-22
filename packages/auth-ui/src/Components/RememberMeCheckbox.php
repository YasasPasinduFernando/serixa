<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\Component\Component;
use Serixa\Forms\Field\Checkbox;
use Serixa\Forms\State\FormState;

/**
 * Remember-me checkbox composed around {@see Checkbox}.
 *
 * @example RememberMeCheckbox::make()->label('Remember me')->formState($state)
 */
final class RememberMeCheckbox extends Component implements AuthWidgetInterface
{
    private Checkbox $checkbox;

    private function __construct()
    {
        parent::__construct();
        $this->checkbox = Checkbox::make('remember')->label('Remember me')->boolean();
    }

    public static function make(): self
    {
        return new self();
    }

    public function label(string $label): self
    {
        $this->checkbox->label($label);

        return $this;
    }

    public function formState(FormState $state): self
    {
        $this->checkbox->formState($state);

        return $this;
    }

    public function field(): Checkbox
    {
        return $this->checkbox;
    }

    public function childrenForTree(): array
    {
        return [$this->checkbox];
    }

    public function render(): string
    {
        return $this->checkbox->render();
    }
}
