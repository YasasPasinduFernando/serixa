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
use Serixa\Forms\Field\Checkbox;
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
            'workspace_name' => 'SERIXA Admin',
            'support_email' => 'support@serixa.dev',
            'timezone' => 'UTC',
            'locale' => 'en',
            'maintenance' => '',
            'allow_invites' => '1',
            'require_2fa' => '1',
            'brand_color' => '#0f766e',
        ];

        $submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
        $request = RequestData::fromArray($submitted ? $_POST : []);

        $result = Validator::make($submitted ? $request->all() : $defaults, [
            'workspace_name' => 'required|min:2|max:120',
            'support_email' => 'required|email',
            'timezone' => 'required|in:UTC,Asia/Colombo,America/New_York,Europe/London',
            'locale' => 'required|in:en,si,ta',
            'maintenance' => 'boolean',
            'allow_invites' => 'boolean',
            'require_2fa' => 'boolean',
            'brand_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ])->validate();

        $csrf = new DemoCsrf();
        $csrfOk = ! $submitted || $csrf->validate($request->string('_token', ''));

        $state = FormBinder::make()
            ->defaults($defaults)
            ->fill($request)
            ->oldInput($submitted ? OldInput::make($request->all()) : OldInput::make())
            ->toFormState()
            ->withErrors($submitted && $csrfOk ? $result->toErrorsBag() : ErrorsBag::make());

        $general = FormBuilder::make()
            ->action('?page=settings')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('workspace_name')->label('Workspace name')->required()->min(2)->max(120))
            ->field(Input::make('support_email')->label('Support email')->type('email')->required()->email())
            ->field(Select::make('timezone')->label('Timezone')->required()->options([
                'UTC' => 'UTC',
                'Asia/Colombo' => 'Asia/Colombo',
                'America/New_York' => 'America/New_York',
                'Europe/London' => 'Europe/London',
            ])->help('Used for invoices, audit timestamps, and digests.'))
            ->field(Select::make('locale')->label('Default locale')->required()->options([
                'en' => 'English',
                'si' => 'Sinhala',
                'ta' => 'Tamil',
            ]))
            ->field(Checkbox::make('maintenance')->label('Show maintenance banner to operators')->boolean())
            ->field(Checkbox::make('allow_invites')->label('Allow admins to invite new users')->boolean())
            ->field(Checkbox::make('require_2fa')->label('Require 2FA for all admins')->boolean())
            ->submit('Save settings')
            ->toWidget();

        $branding = FormBuilder::make()
            ->action('?page=settings')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('brand_color')->label('Primary brand color')->required()->regex('/^#[0-9A-Fa-f]{6}$/')->help('Presentation only — wire to your ThemeInterface.'))
            ->submit('Save branding')
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
            InfoCard::make(Column::make($general)->gap(4))->title('General'),
            InfoCard::make(Column::make($branding)->gap(4))->title('Branding'),
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
