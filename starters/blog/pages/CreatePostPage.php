<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
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
use Serixa\Forms\Field\Textarea;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;

/**
 * New post form with serixa/forms validation demo (no persistence).
 */
final class CreatePostPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $categoryOptions = [];

        foreach (Fixtures::categories() as $category) {
            $categoryOptions[$category['slug']] = $category['name'];
        }

        $defaults = [
            'title' => '',
            'body' => '',
            'status' => 'draft',
            'category' => '',
            'featured' => '',
        ];

        $submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
        $request = RequestData::fromArray($submitted ? $_POST : []);

        $result = Validator::make($submitted ? $request->all() : $defaults, [
            'title' => 'required|min:3|max:200',
            'body' => 'required|min:10',
            'status' => 'required|in:draft,published,scheduled',
            'category' => 'required|in:' . implode(',', array_keys($categoryOptions)),
            'featured' => 'boolean',
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
            ->action('?page=create-post')
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('title')->label('Title')->placeholder('Give your post a title')->required()->min(3)->max(200))
            ->field(Textarea::make('body')->label('Body')->rows(10)->placeholder('Write your post…')->required()->min(10))
            ->field(Select::make('status')->label('Status')->required()->options([
                'draft' => 'Draft',
                'published' => 'Published',
                'scheduled' => 'Scheduled',
            ]))
            ->field(Select::make('category')->label('Category')->required()->placeholder('Choose a category')->options($categoryOptions))
            ->field(Checkbox::make('featured')->label('Feature this post on the homepage')->boolean())
            ->submit('Save post');

        $formBody = [];

        if ($submitted && ! $csrfOk) {
            $formBody[] = ValidationSummary::make(ErrorsBag::make(['_token' => ['Invalid CSRF token (demo).']]));
        } elseif ($submitted && $result->failed()) {
            $formBody[] = ValidationSummary::make($result->toErrorsBag());
        } elseif ($submitted && $result->passed()) {
            $formBody[] = SuccessMessage::make('Post looks valid. Demo only — nothing was saved.');
        }

        $formBody[] = $builder->toWidget();

        $page = Page::make(
            InfoCard::make(Column::make(...$formBody)->gap(4))->title('Post details'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Posts')->href('?page=posts'),
                BreadcrumbItem::make('New post')->current(),
            ))
            ->title('New post')
            ->description('Draft a new post. Validated with serixa/forms — nothing is saved.');

        return BlogLayout::wrap('create-post', $page);
    }
}
