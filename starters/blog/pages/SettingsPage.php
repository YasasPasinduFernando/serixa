<?php

declare(strict_types=1);

namespace App;

use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Page\Page;
use Serixa\Component\Button;
use Serixa\Component\Checkbox;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\HelperText;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Row;
use Serixa\Component\Select;
use Serixa\Document\Document;

/**
 * Site-wide preferences. Everything here is a static form — no settings
 * store, no persistence.
 */
final class SettingsPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $page = Page::make(
            InfoCard::make(
                Form::make(
                    FieldGroup::make(
                        Label::make('Site title')->for('site_title'),
                        Input::make()->id('site_title')->name('site_title')->value('SERIXA Blog'),
                    ),
                    FieldGroup::make(
                        Label::make('Tagline')->for('tagline'),
                        Input::make()->id('tagline')->name('tagline')->value('Notes on building server-rendered UI in PHP.'),
                    ),
                    FieldGroup::make(
                        Label::make('Timezone')->for('timezone'),
                        Select::make()->id('timezone')->name('timezone')->options([
                            'UTC' => 'UTC',
                            'Asia/Colombo' => 'Asia/Colombo',
                            'America/New_York' => 'America/New_York',
                            'Europe/London' => 'Europe/London',
                        ])->value('UTC'),
                        HelperText::make('Used to timestamp posts and comments.'),
                    ),
                    FieldGroup::make(
                        Checkbox::make()->id('allow_comments')->name('allow_comments')->label('Allow comments on new posts')->checked(),
                        Checkbox::make()->id('enable_rss')->name('enable_rss')->label('Enable the RSS feed')->checked(),
                        Checkbox::make()->id('require_approval')->name('require_approval')->label('Require approval before comments are published')->checked(),
                    ),
                    Row::make(Button::make('Save settings')->type('submit')->primary())->gap(3),
                )->method('POST')->action('#'),
            )->title('General'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Settings')->current(),
            ))
            ->title('Settings')
            ->description('Site-wide preferences. UI only — no persistence in this starter.');

        return BlogLayout::wrap('settings', $page);
    }
}
