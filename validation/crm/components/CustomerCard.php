<?php

declare(strict_types=1);

namespace App\Components;

use Serixa\Component\Avatar;
use Serixa\Component\Badge;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Component;
use Serixa\Component\Link;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;

/**
 * Compact customer summary card.
 */
final class CustomerCard extends Component
{
    private string $name = '';

    private string $email = '';

    private string $status = 'Active';

    private string $statusColor = 'success';

    private string $initials = '?';

    private string $href = '#';

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function status(string $status, string $color = 'success'): self
    {
        $this->status = $status;
        $this->statusColor = $color;

        return $this;
    }

    public function initials(string $initials): self
    {
        $this->initials = $initials;

        return $this;
    }

    public function href(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function childrenForTree(): array
    {
        return [$this->card()];
    }

    public function render(): string
    {
        return $this->renderChild($this->card());
    }

    private function card(): WidgetInterface
    {
        return Card::make(
            Column::make([
                Avatar::make()->initials($this->initials)->size('lg')->circle(),
                Text::make($this->name)->as('h3')->size('lg')->weight('semibold'),
                Text::make($this->email)->tone('muted')->size('sm'),
                Badge::make($this->status)->color($this->statusColor),
                Link::make('View profile')->href($this->href),
            ])->gap(2)->alignStart(),
        )->padding('md');
    }
}
