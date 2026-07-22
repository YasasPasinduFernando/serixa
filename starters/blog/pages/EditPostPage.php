<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\DemoCsrf;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\EmptyState\EmptyState;
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
 * Edit post form with serixa/forms validation demo (no persistence).
 */
final class EditPostPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $id = isset($query['id']) && is_numeric($query['id']) ? (int) $query['id'] : 1;
        $post = Fixtures::post($id) ?? Fixtures::post(1);

        if ($post === null) {
            $page = Page::make(
                EmptyState::make()
                    ->icon('document-text')
                    ->title('Post not found')
                    ->description('This post does not exist in the fixture data.'),
            )->title('Edit post');

            return BlogLayout::wrap('edit-post', $page);
        }

        $categoryOptions = [];

        foreach (Fixtures::categories() as $category) {
            $categoryOptions[$category['slug']] = $category['name'];
        }

        $category = Fixtures::category($post['categoryId']);

        $defaults = [
            'title' => $post['title'],
            'body' => $post['body'],
            'status' => $post['status'],
            'category' => $category['slug'] ?? '',
            'featured' => $post['featured'] ? '1' : '',
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
            ->action('?page=edit-post&id=' . $post['id'])
            ->csrf($csrf)
            ->state($state)
            ->field(Input::make('title')->label('Title')->required()->min(3)->max(200))
            ->field(Textarea::make('body')->label('Body')->rows(10)->required()->min(10))
            ->field(Select::make('status')->label('Status')->required()->options([
                'draft' => 'Draft',
                'published' => 'Published',
                'scheduled' => 'Scheduled',
            ]))
            ->field(Select::make('category')->label('Category')->required()->options($categoryOptions))
            ->field(Checkbox::make('featured')->label('Feature this post on the homepage')->boolean())
            ->submit('Save changes');

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
                BreadcrumbItem::make('Edit post')->current(),
            ))
            ->title('Edit post')
            ->description(sprintf('Editing "%s". Validated with serixa/forms — nothing is saved.', $post['title']));

        return BlogLayout::wrap('edit-post', $page);
    }
}
