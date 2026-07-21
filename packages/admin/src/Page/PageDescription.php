<?php

declare(strict_types=1);

namespace Serixa\Admin\Page;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Muted supporting copy shown beneath a {@see PageTitle}.
 *
 * @example PageDescription::make('Manage accounts, roles and access.')
 */
final class PageDescription extends Component implements AdminWidgetInterface
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
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.pageDescription'));

        return sprintf('<p%s>%s</p>', $attrs, $this->escape($this->text));
    }
}
