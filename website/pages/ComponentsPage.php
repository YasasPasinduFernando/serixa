<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Accordion;
use Serixa\Component\AccordionItem;
use Serixa\Component\Alert;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Chip;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Drawer;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\FieldGroup;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Link;
use Serixa\Component\Modal;
use Serixa\Component\Popover;
use Serixa\Component\Progress;
use Serixa\Component\Row;
use Serixa\Component\Select;
use Serixa\Component\Spinner;
use Serixa\Component\SwitchControl;
use Serixa\Component\Tab;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Component\Toast;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;

/** Living component catalog — every preview is rendered by SERIXA itself. */
final class ComponentsPage
{
    private function __construct()
    {
    }

    /** @param array<string, mixed> $query */
    public static function document(array $query = []): Document
    {
        unset($query);

        $hero = Row::make([
            Column::make([
                Badge::make('Living component catalog')->color('primary'),
                Text::make('Beautiful defaults. Real PHP components.')
                    ->as('h1')->size('2xl')->weight('bold')->class('catalog-title'),
                Text::make('Explore the actual controls, feedback, navigation and overlays that ship with SERIXA. Every preview below is semantic server-rendered HTML enhanced by the tiny runtime.')
                    ->tone('muted')->class('catalog-lead'),
                Row::make([
                    Link::make('Read widget docs')->href(DocsCatalog::href('widgets'))->class('btn-primary'),
                    Link::make('Install framework')->href(DocsCatalog::href('installation'))->class('btn-secondary'),
                ])->gap(3)->class('catalog-actions'),
            ])->gap(4)->alignStart(),
            Card::make(
                Text::make('Framework contract')->size('sm')->weight('bold'),
                Text::make('Typed composition')->as('h2')->size('xl')->weight('bold'),
                Text::make('Semantic HTML, accessible states, dark-mode tokens and progressive behavior—without a virtual DOM.')->tone('muted'),
                Progress::make()->value(100)->label('Component catalog readiness'),
            )->class('catalog-contract'),
        ])->gap(6)->spaceBetween()->alignCenter()->class('catalog-hero');

        $actions = self::showcase(
            'Actions & feedback',
            'Clear hierarchy, intentional states and feedback that remains readable in light or dark mode.',
            Row::make([
                Button::make('Open dialog')->primary()->attribute('data-serixa-open-modal', 'catalog-modal'),
                Button::make('Open drawer')->secondary()->attribute('data-serixa-open-drawer', 'catalog-drawer'),
                Button::make('Show toast')->ghost()->attribute('data-serixa-show-toast', 'catalog-toast'),
                Button::make('Unavailable')->disabled(),
            ])->gap(3)->class('catalog-control-row'),
            Alert::make()->tone('success')->title('Accessible by default')
                ->body('Dismiss this alert to verify progressive enhancement.')->dismissible(),
            Row::make([
                Badge::make('Primary')->color('primary'),
                Badge::make('Success')->color('success'),
                Badge::make('Warning')->color('warning'),
                Badge::make('Danger')->color('danger'),
                Chip::make('Dismissible chip')->color('primary')->dismissible(),
                Spinner::make()->size('sm')->label('Loading preview'),
            ])->gap(2)->alignCenter()->class('catalog-control-row'),
        );

        $forms = self::showcase(
            'Form foundations',
            'Labels, native controls and validation-ready attributes stay explicit and composable.',
            Row::make([
                FieldGroup::make(
                    Label::make('Workspace name')->for('catalog-workspace'),
                    Input::make()->id('catalog-workspace')->name('workspace')
                        ->placeholder('Acme Studio')->autocomplete('organization'),
                ),
                FieldGroup::make(
                    Label::make('Release channel')->for('catalog-channel'),
                    Select::make()->id('catalog-channel')->name('channel')
                        ->options(['stable' => 'Stable', 'preview' => 'Preview'])->value('stable'),
                ),
            ])->gap(4)->class('catalog-form-grid'),
            SwitchControl::make()->name('updates')->label('Receive component release notes')->checked(),
        );

        $navigation = self::showcase(
            'Navigation & disclosure',
            'Keyboard-friendly patterns with linked ARIA state and predictable focus behavior.',
            Tabs::make()
                ->tab(Tab::make('Overview')->active()->content(Text::make('Compose UI from small, typed widgets.')))
                ->tab(Tab::make('Theming')->content(Text::make('Swap tokens while component contracts remain stable.')))
                ->tab(Tab::make('Runtime')->content(Text::make('Add interactions without hydration or a client state engine.'))),
            Accordion::make()
                ->item(AccordionItem::make()->title('Does SERIXA require JavaScript?')->content(Text::make('No. Core output is server-rendered HTML; JavaScript progressively enhances interactive modules.'))->open())
                ->item(AccordionItem::make()->title('Can I create my own components?')->content(Text::make('Yes. Compose widgets or extend the base Widget contract for a reusable API.'))),
            Row::make([
                Dropdown::make()->trigger(Button::make('Open menu')->secondary())->items([
                    DropdownItem::make('Documentation')->href('/docs-widgets/'),
                    DropdownItem::make('Framework source')->href('https://github.com/YasasPasinduFernando/serixa/tree/main/framework'),
                ]),
                Popover::make()->ariaLabel('SERIXA runtime note')
                    ->trigger(Button::make('Why progressive?')->ghost())
                    ->content(Text::make('The page remains useful before the runtime loads.')),
            ])->gap(3)->class('catalog-control-row'),
        );

        $code = Column::make([
            Badge::make('Composable API')->color('primary'),
            Text::make('The preview and the code share the same vocabulary.')->as('h2')->size('2xl')->weight('bold'),
            Text::make('Start with framework primitives, then compose app-specific components without leaving PHP.')->tone('muted'),
            CodeSample::make(<<<'PHP'
use Serixa\Component\{Alert, Button, Card, Column, Text};

$releaseCard = Card::make(
    Column::make([
        Text::make('Release ready')->as('h2')->weight('bold'),
        Alert::make()->tone('success')->body('All checks passed.'),
        Button::make('Deploy')->primary(),
    ])->gap(4)
);
PHP),
        ])->gap(4)->alignStart()->class('catalog-code');

        $content = Container::make(
            Column::make([
                $hero,
                Row::make([$actions, $forms])->gap(5)->class('catalog-grid'),
                Row::make([$navigation, $code])->gap(5)->class('catalog-grid'),
                Row::make([
                    Column::make([
                        Text::make('Build your own design language on a reliable core.')->as('h2')->size('2xl')->weight('bold'),
                        Text::make('Use the defaults today, customize the theme tomorrow, and keep the semantic component API intact.')->tone('muted'),
                    ])->gap(2),
                    Link::make('Start with installation')->href(DocsCatalog::href('installation'))->class('btn-primary'),
                ])->gap(4)->spaceBetween()->alignCenter()->class('catalog-cta'),
            ])->gap(8)->alignStretch(),
        )->maxWidth('xl')->padding('lg')->class('catalog-container');

        $overlays = [
            Modal::make()->id('catalog-modal')->title('Production-ready dialog')
                ->body(Text::make('Focus is trapped inside, Escape closes it, and focus returns to the trigger.'))
                ->footer(Button::make('Close')->secondary()->attribute('data-serixa-close', true)),
            Drawer::make()->id('catalog-drawer')->title('Component drawer')->body(Column::make([
                Text::make('Responsive overlay behavior with accessible dialog semantics.'),
                Alert::make()->tone('info')->body('Use Tab to verify the focus boundary.'),
                Button::make('Close drawer')->secondary()->attribute('data-serixa-close', true),
            ])->gap(4)),
            Toast::make()->id('catalog-toast')->message('SERIXA runtime is working.')->tone('success')->open(false),
        ];

        return SiteLayout::wrap(
            'components',
            Column::make([$content, ...$overlays])->gap(0)->alignStretch(),
            'Component Catalog',
        );
    }

    private static function showcase(string $title, string $description, WidgetInterface|string ...$children): Card
    {
        return Card::make(
            Text::make($title)->as('h2')->size('xl')->weight('bold'),
            Text::make($description)->tone('muted'),
            ...$children,
        )->class('catalog-card');
    }
}
