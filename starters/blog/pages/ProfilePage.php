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
use Serixa\Component\Button;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Row;
use Serixa\Component\Textarea;
use Serixa\Document\Document;

/**
 * The signed-in author's own account page: read-only summary plus an
 * editable (UI only) profile form.
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
        $user = Fixtures::user(1) ?? [
            'name' => 'Ada Lovelace',
            'email' => 'ada@serixa.dev',
            'role' => 'Admin',
            'status' => 'success',
            'statusLabel' => 'Active',
            'joined' => '2025-11-02',
        ];

        $page = Page::make(
            InfoCard::make(
                DescriptionList::make(
                    DescriptionItem::make('Name', $user['name']),
                    DescriptionItem::make('Email', $user['email']),
                    DescriptionItem::make('Role', $user['role']),
                    DescriptionItem::make('Status', StatusBadge::make($user['statusLabel'])->status($user['status'])),
                    DescriptionItem::make('Joined', $user['joined']),
                ),
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
                        Label::make('Bio')->for('bio'),
                        Textarea::make('Building things with SERIXA.')->id('bio')->name('bio')->rows(4),
                    ),
                    Row::make(Button::make('Save profile')->type('submit')->primary())->gap(3),
                )->method('POST')->action('#'),
            )->title('Edit profile'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Profile')->current(),
            ))
            ->title('Profile')
            ->description('Your account details. UI only — changes are not persisted.');

        return BlogLayout::wrap('profile', $page);
    }
}
