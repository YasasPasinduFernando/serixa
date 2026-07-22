<?php

declare(strict_types=1);

namespace App;

use App\Support\DemoCsrf;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Page\Page;
use Serixa\Component\Column;
use Serixa\Document\Document;
use Serixa\Forms\Binding\FormBinder;
use Serixa\Forms\Binding\RequestData;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Field\Input;
use Serixa\Forms\Field\Select;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;

/**
 * Workspace settings with serixa/forms validation demo (no persistence).
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
        $defaults = [
            'workspace_name' => 'SERIXA CRM',
            'timezone' => 'UTC',
        ];

        $submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
        $request = RequestData::fromArray($submitted ? $_POST : []);

        $result = Validator::make($submitted ? $request->all() : $defaults, [
            'workspace_name' => 'required|min:2|max:120',
            'timezone' => 'required|in:UTC,Asia/Colombo,America/New_York,Europe/London',
        ])->validate();

        $csrf = new DemoCsrf();
        $csrfOk = ! $submitted || $csrf->validate($request->string('_token', ''));

        $state = FormBinder::make()
            ->defaults($defaults)
            ->fill($request)
            ->oldInput($submitted ? OldInput::make($request->all()) : OldInput::make())
            ->toFormState()
            ->withErrors($submitted && $csrfOk ? $result->toErrorsBag() : ErrorsBag::make());

        $form = FormBuilder::make()
            ->action('?page=settings')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('workspace_name')->label('Workspace name')->required()->min(2)->max(120))
            ->field(Select::make('timezone')->label('Timezone')->required()->options([
                'UTC' => 'UTC',
                'Asia/Colombo' => 'Asia/Colombo',
                'America/New_York' => 'America/New_York',
                'Europe/London' => 'Europe/London',
            ])->help('Used for meetings, invoices, and digests.'))
            ->submit('Save settings')
            ->toWidget();

        $messages = [];

        if ($submitted && ! $csrfOk) {
            $messages[] = ValidationSummary::make(ErrorsBag::make(['_token' => ['Invalid CSRF token (demo).']]));
        } elseif ($submitted && $result->failed()) {
            $messages[] = ValidationSummary::make($result->toErrorsBag());
        } elseif ($submitted && $result->passed()) {
            $messages[] = SuccessMessage::make('Settings look valid. Demo only — nothing was saved.');
        }

        $page = Page::make(...array_merge($messages, [
            InfoCard::make(Column::make($form)->gap(4))->title('General'),
        ]))
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Settings')->current(),
            ))
            ->title('Settings')
            ->description('Workspace-wide preferences. Validated with serixa/forms — no persistence.');

        return PanelLayout::wrap('settings', $page);
    }
}
