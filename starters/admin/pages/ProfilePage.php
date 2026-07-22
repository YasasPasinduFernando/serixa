<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\DemoCsrf;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Components\DescriptionItem;
use Serixa\Admin\Components\DescriptionList;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\Page\Page;
use Serixa\Component\Avatar;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Document\Document;
use Serixa\Forms\Binding\FormBinder;
use Serixa\Forms\Binding\RequestData;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Field\Checkbox;
use Serixa\Forms\Field\Input;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;

/**
 * Operator profile with serixa/forms validation demo (no persistence).
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

        $defaults = [
            'name' => $user['name'],
            'email' => $user['email'],
            'title' => $user['title'],
            'current_password' => '',
            'new_password' => '',
            'two_factor' => '1',
            'session_alerts' => '1',
            'notify_email' => '1',
            'notify_orders' => '1',
            'notify_security' => '1',
            'notify_digest' => '',
        ];

        $submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
        $request = RequestData::fromArray($submitted ? $_POST : []);

        $result = Validator::make($submitted ? $request->all() : $defaults, [
            'name' => 'required|min:2|max:120',
            'email' => 'required|email|max:255',
            'title' => 'required|max:120',
            'current_password' => 'nullable|min:8',
            'new_password' => 'nullable|min:12',
            'two_factor' => 'boolean',
            'session_alerts' => 'boolean',
            'notify_email' => 'boolean',
            'notify_orders' => 'boolean',
            'notify_security' => 'boolean',
            'notify_digest' => 'boolean',
        ])->validate();

        $csrf = new DemoCsrf();
        $csrfOk = ! $submitted || $csrf->validate($request->string('_token', ''));

        $state = FormBinder::make()
            ->defaults($defaults)
            ->fill($request)
            ->oldInput($submitted ? OldInput::make($request->all()) : OldInput::make())
            ->toFormState()
            ->withErrors($submitted && $csrfOk ? $result->toErrorsBag() : ErrorsBag::make());

        $profileForm = FormBuilder::make()
            ->action('?page=profile')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('name')->label('Display name')->required()->min(2)->max(120))
            ->field(Input::make('email')->label('Email')->type('email')->required()->email()->max(255))
            ->field(Input::make('title')->label('Job title')->required()->max(120))
            ->submit('Save profile')
            ->toWidget();

        $securityForm = FormBuilder::make()
            ->action('?page=profile')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('current_password')->label('Current password')->type('password')->nullable()->min(8))
            ->field(Input::make('new_password')->label('New password')->type('password')->nullable()->min(12)->help('Use at least 12 characters. UI only — nothing is stored.'))
            ->field(Checkbox::make('two_factor')->label('Require two-factor authentication')->boolean())
            ->field(Checkbox::make('session_alerts')->label('Email me about new browser sessions')->boolean())
            ->submit('Update security')
            ->toWidget();

        $notifyForm = FormBuilder::make()
            ->action('?page=profile')
            ->csrf($csrf)
            ->state($state)
            ->field(Checkbox::make('notify_email')->label('Product and billing emails')->boolean())
            ->field(Checkbox::make('notify_orders')->label('Order and invoice alerts')->boolean())
            ->field(Checkbox::make('notify_security')->label('Security alerts')->boolean())
            ->field(Checkbox::make('notify_digest')->label('Weekly workspace digest')->boolean())
            ->submit('Save notification preferences')
            ->toWidget();

        $messages = [];

        if ($submitted && ! $csrfOk) {
            $messages[] = ValidationSummary::make(ErrorsBag::make(['_token' => ['Invalid CSRF token (demo).']]));
        } elseif ($submitted && $result->failed()) {
            $messages[] = ValidationSummary::make($result->toErrorsBag());
        } elseif ($submitted && $result->passed()) {
            $messages[] = SuccessMessage::make('Profile looks valid. Demo only — nothing was saved.');
        }

        $page = Page::make(...array_merge($messages, [
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
            InfoCard::make(Column::make($profileForm)->gap(4))->title('Edit profile'),
            InfoCard::make(Column::make($securityForm)->gap(4))->title('Security settings'),
            InfoCard::make(Column::make($notifyForm)->gap(4))->title('Notification settings'),
        ]))
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Profile')->current(),
            ))
            ->title('Profile')
            ->description('Your account details. Validated with serixa/forms — changes are not persisted.');

        return PanelLayout::wrap('profile', $page);
    }
}
