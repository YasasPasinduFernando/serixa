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
use Serixa\Component\Column;
use Serixa\Document\Document;
use Serixa\Forms\Binding\FormBinder;
use Serixa\Forms\Binding\RequestData;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
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
        ];

        $submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
        $request = RequestData::fromArray($submitted ? $_POST : []);

        $result = Validator::make($submitted ? $request->all() : $defaults, [
            'name' => 'required|min:2|max:120',
            'email' => 'required|email|max:255',
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
            ->submit('Save profile')
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
                DescriptionList::make(
                    DescriptionItem::make('Name', $user['name']),
                    DescriptionItem::make('Email', $user['email']),
                    DescriptionItem::make('Title', $user['title']),
                    DescriptionItem::make('Role', $user['role']),
                    DescriptionItem::make('Team', $user['team']),
                    DescriptionItem::make('Status', StatusBadge::make('Active')->status('success')),
                ),
            )->title('Account'),
            InfoCard::make(Column::make($profileForm)->gap(4))->title('Edit profile'),
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
