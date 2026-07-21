<?php

declare(strict_types=1);

namespace Serixa\Admin\Page;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Page heading (`<h1>`).
 *
 * @example PageTitle::make('Users')
 */
final class PageTitle extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

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
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.pageTitle'));

        return sprintf('<h1%s>%s</h1>', $attrs, $this->escape($this->text));
    }
}
