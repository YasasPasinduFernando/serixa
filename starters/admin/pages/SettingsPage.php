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
 * Workspace-wide preferences. Everything here is a static form — no settings
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
                        Label::make('Workspace name')->for('workspace_name'),
                        Input::make()->id('workspace_name')->name('workspace_name')->value('SERIXA Admin'),
                    ),
                    FieldGroup::make(
                        Label::make('Support email')->for('support_email'),
                        Input::make()->id('support_email')->name('support_email')->type('email')->value('support@serixa.dev'),
                    ),
                    FieldGroup::make(
                        Label::make('Timezone')->for('timezone'),
                        Select::make()->id('timezone')->name('timezone')->options([
                            'UTC' => 'UTC',
                            'Asia/Colombo' => 'Asia/Colombo',
                            'America/New_York' => 'America/New_York',
                            'Europe/London' => 'Europe/London',
                        ])->value('UTC'),
                        HelperText::make('Used for invoices, audit timestamps, and digests.'),
                    ),
                    FieldGroup::make(
                        Label::make('Default locale')->for('locale'),
                        Select::make()->id('locale')->name('locale')->options([
                            'en' => 'English',
                            'si' => 'Sinhala',
                            'ta' => 'Tamil',
                        ])->value('en'),
                    ),
                    FieldGroup::make(
                        Checkbox::make()->id('maintenance')->name('maintenance')->label('Show maintenance banner to operators'),
                        Checkbox::make()->id('allow_invites')->name('allow_invites')->label('Allow admins to invite new users')->checked(),
                        Checkbox::make()->id('require_2fa')->name('require_2fa')->label('Require 2FA for all admins')->checked(),
                    ),
                    Row::make(Button::make('Save settings')->type('submit')->primary())->gap(3),
                )->method('POST')->action('#'),
            )->title('General'),
            InfoCard::make(
                Form::make(
                    FieldGroup::make(
                        Label::make('Primary brand color')->for('brand_color'),
                        Input::make()->id('brand_color')->name('brand_color')->value('#0f766e'),
                        HelperText::make('Presentation only in this starter — wire to your ThemeInterface.'),
                    ),
                    Row::make(Button::make('Save branding')->type('submit')->primary())->gap(3),
                )->method('POST')->action('#'),
            )->title('Branding'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Settings')->current(),
            ))
            ->title('Settings')
            ->description('Workspace-wide preferences. UI only — no persistence in this starter.');

        return PanelLayout::wrap('settings', $page);
    }
}
