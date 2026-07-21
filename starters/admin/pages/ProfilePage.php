<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Components\DescriptionItem;
use Serixa\Admin\Components\DescriptionList;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\Page\Page;
use Serixa\Component\Avatar;
use Serixa\Component\Button;
use Serixa\Component\Checkbox;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\HelperText;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Row;
use Serixa\Document\Document;

/**
 * Signed-in operator profile: avatar, account summary, security and
 * notification preference forms (UI only — no persistence).
 */
final class ProfilePage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $user = Fixtures::currentUser();

        $page = Page::make(
            InfoCard::make(
                Row::make(
                    Avatar::make()->initials($user['initials'])->size('xl')->circle(),
                    DescriptionList::make(
                        DescriptionItem::make('Name', $user['name']),
                        DescriptionItem::make('Email', $user['email']),
                        DescriptionItem::make('Title', $user['title']),
                        DescriptionItem::make('Role', $user['role']),
                        DescriptionItem::make('Department', $user['department']),
                        DescriptionItem::make('Status', StatusBadge::make('Active')->status('success')),
                        DescriptionItem::make('Joined', $user['joined']),
                        DescriptionItem::make('Timezone', $user['timezone']),
                    ),
                )->gap(6),
            )->title('Account'),
            InfoCard::make(
                Form::make(
                    FieldGroup::make(
                        Label::make('Display name')->for('name'),
                        Input::make()->id('name')->name('name')->value($user['name']),
                    ),
                    FieldGroup::make(
                        Label::make('Email')->for('email'),
                        Input::make()->id('email')->name('email')->type('email')->value($user['email']),
                    ),
                    FieldGroup::make(
                        Label::make('Job title')->for('title'),
                        Input::make()->id('title')->name('title')->value($user['title']),
                    ),
                    Row::make(Button::make('Save profile')->type('submit')->primary())->gap(3),
                )->method('POST')->action('#'),
            )->title('Edit profile'),
            InfoCard::make(
                Form::make(
                    FieldGroup::make(
                        Label::make('Current password')->for('current_password'),
                        Input::make()->id('current_password')->name('current_password')->type('password'),
                    ),
                    FieldGroup::make(
                        Label::make('New password')->for('new_password'),
                        Input::make()->id('new_password')->name('new_password')->type('password'),
                        HelperText::make('Use at least 12 characters. UI only — nothing is stored.'),
                    ),
                    FieldGroup::make(
                        Checkbox::make()->id('two_factor')->name('two_factor')->label('Require two-factor authentication')->checked(),
                        Checkbox::make()->id('session_alerts')->name('session_alerts')->label('Email me about new browser sessions')->checked(),
                    ),
                    Row::make(Button::make('Update security')->type('submit')->primary())->gap(3),
                )->method('POST')->action('#'),
            )->title('Security settings'),
            InfoCard::make(
                Form::make(
                    FieldGroup::make(
                        Checkbox::make()->id('notify_email')->name('notify_email')->label('Product and billing emails')->checked(),
                        Checkbox::make()->id('notify_orders')->name('notify_orders')->label('Order and invoice alerts')->checked(),
                        Checkbox::make()->id('notify_security')->name('notify_security')->label('Security alerts')->checked(),
                        Checkbox::make()->id('notify_digest')->name('notify_digest')->label('Weekly workspace digest'),
                    ),
                    Row::make(Button::make('Save notification preferences')->type('submit')->primary())->gap(3),
                )->method('POST')->action('#'),
            )->title('Notification settings'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Profile')->current(),
            ))
            ->title('Profile')
            ->description('Your account details. UI only — changes are not persisted.');

        return PanelLayout::wrap('profile', $page);
    }
}
