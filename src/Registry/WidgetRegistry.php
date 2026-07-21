<?php

declare(strict_types=1);

namespace Serixa\Registry;

use Serixa\Component\AppBar;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Center;
use Serixa\Component\Checkbox;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Divider;
use Serixa\Component\ErrorText;
use Serixa\Component\Expanded;
use Serixa\Component\FieldGroup;
use Serixa\Component\Footer;
use Serixa\Component\Form;
use Serixa\Component\HelperText;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Link;
use Serixa\Component\MainContent;
use Serixa\Component\Padding;
use Serixa\Component\PasswordInput;
use Serixa\Component\Radio;
use Serixa\Component\Row;
use Serixa\Component\Scaffold;
use Serixa\Component\Select;
use Serixa\Component\Sidebar;
use Serixa\Component\SizedBox;
use Serixa\Component\Spacer;
use Serixa\Component\Stack;
use Serixa\Component\SwitchControl;
use Serixa\Component\Text;
use Serixa\Component\Textarea;
use Serixa\Contracts\WidgetInterface;
use Serixa\Exception\RegistryException;

/**
 * Registers widget types and their metadata for discovery.
 */
final class WidgetRegistry
{
    /** @var array<string, WidgetMetadata> */
    private array $widgets = [];

    public function register(WidgetMetadata $metadata): void
    {
        if ($metadata->name() === '') {
            throw new RegistryException('Widget metadata name cannot be empty.');
        }

        $this->widgets[$metadata->name()] = $metadata;
    }

    public function unregister(string $name): void
    {
        unset($this->widgets[$name]);
    }

    public function find(string $name): ?WidgetMetadata
    {
        return $this->widgets[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->widgets[$name]);
    }

    /**
     * @return array<string, WidgetMetadata>
     */
    public function all(): array
    {
        return $this->widgets;
    }

    /**
     * @return class-string<WidgetInterface>|null
     */
    public function classFor(string $name): ?string
    {
        $meta = $this->find($name);

        if ($meta === null || $meta->className() === '') {
            return null;
        }

        /** @var class-string<WidgetInterface> $class */
        $class = $meta->className();

        return $class;
    }

    /**
     * Seed registry with built-in SERIXA widgets.
     */
    public static function withDefaults(): self
    {
        $registry = new self();

        foreach (self::defaultMetadata() as $metadata) {
            $registry->register($metadata);
        }

        return $registry;
    }

    /**
     * @return list<WidgetMetadata>
     */
    public static function defaultMetadata(): array
    {
        return [
            new WidgetMetadata('button', 'input', 'Interactive button', ['variant', 'size', 'disabled', 'rounded'], ['click'], false, '0.1.0', Button::class),
            new WidgetMetadata('text', 'typography', 'Semantic text node', ['size', 'weight', 'tone', 'as'], [], false, '0.1.0', Text::class),
            new WidgetMetadata('card', 'surface', 'Content card', ['title', 'padding'], [], true, '0.1.0', Card::class),
            new WidgetMetadata('container', 'layout', 'Page width container', ['maxWidth', 'padding'], [], true, '0.1.0', Container::class),
            new WidgetMetadata('row', 'layout', 'Horizontal flex row', ['justify', 'align', 'gap'], [], true, '0.2.0', Row::class),
            new WidgetMetadata('column', 'layout', 'Vertical flex column', ['align', 'gap'], [], true, '0.2.0', Column::class),
            new WidgetMetadata('center', 'layout', 'Centers children', [], [], true, '0.2.0', Center::class),
            new WidgetMetadata('padding', 'layout', 'Padding wrapper', ['all', 'x', 'y'], [], true, '0.2.0', Padding::class),
            new WidgetMetadata('spacer', 'layout', 'Flex spacer', [], [], false, '0.2.0', Spacer::class),
            new WidgetMetadata('expanded', 'layout', 'Flex grow wrapper', [], [], true, '0.2.0', Expanded::class),
            new WidgetMetadata('sizedBox', 'layout', 'Fixed width/height box', ['width', 'height'], [], true, '0.2.0', SizedBox::class),
            new WidgetMetadata('stack', 'layout', 'Layered stack', [], [], true, '0.2.0', Stack::class),
            new WidgetMetadata('divider', 'layout', 'Separator rule', ['orientation', 'spacing', 'label'], [], false, '0.6.0', Divider::class),
            new WidgetMetadata('scaffold', 'layout', 'App shell slots', ['appBar', 'sidebar', 'body', 'footer'], [], true, '0.5.2', Scaffold::class),
            new WidgetMetadata('appBar', 'layout', 'Top bar content', ['title', 'actions'], [], true, '0.6.0', AppBar::class),
            new WidgetMetadata('sidebar', 'layout', 'Sidebar navigation', [], [], true, '0.6.0', Sidebar::class),
            new WidgetMetadata('mainContent', 'layout', 'Semantic main region', [], [], true, '0.6.0', MainContent::class),
            new WidgetMetadata('footer', 'layout', 'Footer content', [], [], true, '0.6.0', Footer::class),
            new WidgetMetadata('form', 'form', 'HTML form element', ['method', 'action', 'enctype'], [], true, '0.6.0', Form::class),
            new WidgetMetadata('label', 'form', 'Form label', ['for'], [], false, '0.6.0', Label::class),
            new WidgetMetadata('input', 'form', 'Text input', ['type', 'name', 'placeholder'], [], false, '0.6.0', Input::class),
            new WidgetMetadata('passwordInput', 'form', 'Password input', ['name', 'placeholder'], [], false, '0.6.0', PasswordInput::class),
            new WidgetMetadata('textarea', 'form', 'Multi-line input', ['rows', 'name'], [], false, '0.6.0', Textarea::class),
            new WidgetMetadata('checkbox', 'form', 'Checkbox', ['name', 'checked', 'label'], [], false, '0.6.0', Checkbox::class),
            new WidgetMetadata('radio', 'form', 'Radio button', ['name', 'value', 'checked', 'label'], [], false, '0.6.0', Radio::class),
            new WidgetMetadata('switch', 'form', 'Switch control (PHP: SwitchControl)', ['name', 'checked', 'label'], [], false, '0.6.0', SwitchControl::class),
            new WidgetMetadata('select', 'form', 'Select list', ['name', 'options', 'value'], [], false, '0.6.0', Select::class),
            new WidgetMetadata('fieldGroup', 'form', 'Label + control group', [], [], true, '0.6.0', FieldGroup::class),
            new WidgetMetadata('helperText', 'form', 'Helper copy', [], [], false, '0.6.0', HelperText::class),
            new WidgetMetadata('errorText', 'form', 'Error copy', [], [], false, '0.6.0', ErrorText::class),
            new WidgetMetadata('link', 'navigation', 'Anchor link', ['href', 'active', 'target'], [], false, '0.6.0', Link::class),
        ];
    }
}
