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
use Serixa\Forms\Field\Textarea;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;

/**
 * Signed-in author profile with serixa/forms validation demo (no persistence).
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

        $defaults = [
            'name' => $user['name'],
            'email' => $user['email'],
            'bio' => 'Building things with SERIXA.',
        ];

        $submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
        $request = RequestData::fromArray($submitted ? $_POST : []);

        $result = Validator::make($submitted ? $request->all() : $defaults, [
            'name' => 'required|min:2|max:120',
            'email' => 'required|email|max:255',
            'bio' => 'nullable|max:500',
        ])->validate();

        $csrf = new DemoCsrf();
        $csrfOk = ! $submitted || $csrf->validate($request->string('_token', ''));

        $state = FormBinder::make()
            ->defaults($defaults)
            ->fill($request)
            ->oldInput($submitted ? OldInput::make($request->all()) : OldInput::make())
            ->toFormState()
            ->withErrors($submitted && $csrfOk ? $result->toErrorsBag() : ErrorsBag::make());

        $builder = FormBuilder::make()
            ->action('?page=profile')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('name')->label('Display name')->required()->min(2)->max(120))
            ->field(Input::make('email')->label('Email')->type('email')->required()->email()->max(255))
            ->field(Textarea::make('bio')->label('Bio')->rows(4)->nullable()->max(500)->help('UI only — changes are not persisted.'))
            ->submit('Save profile');

        $formBody = [];

        if ($submitted && ! $csrfOk) {
            $formBody[] = ValidationSummary::make(ErrorsBag::make(['_token' => ['Invalid CSRF token (demo).']]));
        } elseif ($submitted && $result->failed()) {
            $formBody[] = ValidationSummary::make($result->toErrorsBag());
        } elseif ($submitted && $result->passed()) {
            $formBody[] = SuccessMessage::make('Profile looks valid. Demo only — nothing was saved.');
        }

        $formBody[] = $builder->toWidget();

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
            InfoCard::make(Column::make(...$formBody)->gap(4))->title('Edit profile'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Profile')->current(),
            ))
            ->title('Profile')
            ->description('Your account details. Validation runs via serixa/forms; nothing is persisted.');

        return BlogLayout::wrap('profile', $page);
    }
}
