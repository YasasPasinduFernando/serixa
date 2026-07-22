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
 * Site-wide preferences with serixa/forms validation demo (no persistence).
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
            'site_title' => 'SERIXA Blog',
            'tagline' => 'Notes on building server-rendered UI in PHP.',
            'timezone' => 'UTC',
            'allow_comments' => '1',
            'enable_rss' => '1',
            'require_approval' => '1',
        ];

        $submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
        $request = RequestData::fromArray($submitted ? $_POST : []);

        $result = Validator::make($submitted ? $request->all() : $defaults, [
            'site_title' => 'required|min:2|max:120',
            'tagline' => 'required|max:255',
            'timezone' => 'required|in:UTC,Asia/Colombo,America/New_York,Europe/London',
            'allow_comments' => 'boolean',
            'enable_rss' => 'boolean',
            'require_approval' => 'boolean',
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
            ->action('?page=settings')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('site_title')->label('Site title')->required()->min(2)->max(120))
            ->field(Input::make('tagline')->label('Tagline')->required()->max(255))
            ->field(Select::make('timezone')->label('Timezone')->required()->options([
                'UTC' => 'UTC',
                'Asia/Colombo' => 'Asia/Colombo',
                'America/New_York' => 'America/New_York',
                'Europe/London' => 'Europe/London',
            ])->help('Used to timestamp posts and comments.'))
            ->field(Checkbox::make('allow_comments')->label('Allow comments on new posts')->boolean())
            ->field(Checkbox::make('enable_rss')->label('Enable the RSS feed')->boolean())
            ->field(Checkbox::make('require_approval')->label('Require approval before comments are published')->boolean())
            ->submit('Save settings');

        $formBody = [];

        if ($submitted && ! $csrfOk) {
            $formBody[] = ValidationSummary::make(ErrorsBag::make(['_token' => ['Invalid CSRF token (demo).']]));
        } elseif ($submitted && $result->failed()) {
            $formBody[] = ValidationSummary::make($result->toErrorsBag());
        } elseif ($submitted && $result->passed()) {
            $formBody[] = SuccessMessage::make('Settings look valid. Demo only — nothing was saved.');
        }

        $formBody[] = $builder->toWidget();

        $page = Page::make(
            InfoCard::make(Column::make(...$formBody)->gap(4))->title('General'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Settings')->current(),
            ))
            ->title('Settings')
            ->description('Site-wide preferences. Validated with serixa/forms — no persistence.');

        return BlogLayout::wrap('settings', $page);
    }
}
