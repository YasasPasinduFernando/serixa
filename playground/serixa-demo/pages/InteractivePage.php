<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Accordion;
use Serixa\Component\AccordionItem;
use Serixa\Component\Alert;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Chip;
use Serixa\Component\Drawer;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\Modal;
use Serixa\Component\Popover;
use Serixa\Component\Progress;
use Serixa\Component\Row;
use Serixa\Component\Skeleton;
use Serixa\Component\Spinner;
use Serixa\Component\Tab;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Component\Toast;
use Serixa\Component\Tooltip;
use Serixa\Document\Document;
use SerixaDemo\Components\PageSection;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Shell;

final class InteractivePage
{
    public static function document(): Document
    {
        $content = PageSection::make(
            'Interactive components',
            'SSR markup + Serixa runtime modules (no VDOM).',
            [],
            Alert::make()->tone('info')->title('Progressive enhancement')->body('These widgets work as HTML; JS adds behavior.'),
            Row::make([
                Badge::make('Badge')->color('primary'),
                Chip::make('Chip')->dismissible(),
                Spinner::make()->size('sm'),
                Progress::make()->value(40)->label('Progress'),
            ])->gap(3)->alignCenter(),
            Skeleton::make()->lines(2),
            Dropdown::make()
                ->trigger(Button::make('Dropdown')->secondary())
                ->items([
                    DropdownItem::make('Profile'),
                    DropdownItem::make('Settings'),
                ]),
            Popover::make()
                ->trigger(Button::make('Popover')->ghost())
                ->content(Text::make('Anchored panel content.')),
            Tooltip::make()->text('Helpful tip')->child(Button::make('Tooltip')->ghost()),
            Tabs::make()
                ->tab(Tab::make('One')->active()->content(Text::make('Panel one')))
                ->tab(Tab::make('Two')->content(Text::make('Panel two'))),
            Accordion::make()
                ->item(AccordionItem::make()->title('Accordion')->content(Text::make('Disclosure body'))->open()),
            Button::make('Open modal')->primary()->attribute('data-serixa-open-modal', 'demo-modal'),
            Button::make('Open drawer')->secondary()->attribute('data-serixa-open-drawer', 'demo-drawer'),
            Button::make('Show toast')->ghost()->attribute('data-serixa-show-toast', 'demo-toast'),
            Modal::make()->id('demo-modal')->title('Demo modal')->body(Text::make('Focus trap + Escape.'))->open(false),
            Drawer::make()->id('demo-drawer')->title('Demo drawer')->body(Text::make('Side panel.'))->open(false),
            Toast::make()->id('demo-toast')->message('Hello from ToastModule')->open(false),
        );

        return DocumentFactory::page('Interactive', Shell::app('interactive', $content));
    }
}
