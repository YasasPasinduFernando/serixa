<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;
use Serixa\Icons\Component\Icon;

/**
 * Social provider links styled as buttons. Apps supply hrefs — no OAuth logic.
 *
 * @example SocialLoginButtons::make()->provider('Google', '/auth/google', 'globe-alt')
 */
final class SocialLoginButtons extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    /** @var list<array{name: string, href: string, icon: ?string}> */
    private array $providers = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function provider(string $name, string $href, ?string $icon = null): self
    {
        $this->providers[] = [
            'name' => $name,
            'href' => $href,
            'icon' => $icon,
        ];

        return $this;
    }

    /**
     * @param list<array{name: string, href: string, icon?: ?string}|array{0: string, 1: string, 2?: ?string}> $providers
     */
    public function providers(array $providers): self
    {
        foreach ($providers as $provider) {
            if (array_is_list($provider)) {
                $this->provider(
                    (string) $provider[0],
                    (string) $provider[1],
                    isset($provider[2]) ? (string) $provider[2] : null,
                );

                continue;
            }

            $this->provider(
                (string) $provider['name'],
                (string) $provider['href'],
                isset($provider['icon']) ? (string) $provider['icon'] : null,
            );
        }

        return $this;
    }

    public function render(): string
    {
        if ($this->providers === []) {
            return '';
        }

        $buttons = '';

        foreach ($this->providers as $provider) {
            $iconHtml = '';

            if ($provider['icon'] !== null && $provider['icon'] !== '') {
                $iconHtml = Icon::make($provider['icon'])->size('sm')->decorative()->render();
            }

            $label = sprintf(
                'Continue with %s',
                $this->escape($provider['name']),
            );

            $attrs = $this->buildStructuralAttributeString(
                $this->theme()->classes('auth.social.button'),
                ['href' => $provider['href']],
            );

            $buttons .= sprintf('<a%s>%s%s</a>', $attrs, $iconHtml, $label);
        }

        $attrs = $this->buildAttributeString($this->theme()->classes('auth.social'));

        return Html::tag('div', $attrs, $buttons);
    }
}
