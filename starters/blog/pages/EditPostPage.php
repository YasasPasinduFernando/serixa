<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Component\Button;
use Serixa\Component\Checkbox;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Row;
use Serixa\Component\Select;
use Serixa\Component\Textarea;
use Serixa\Document\Document;

/**
 * Edit post form, prefilled from a fixture looked up by `?id=`.
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

        $page = Page::make(
            InfoCard::make(self::form($post))->title('Post details'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Posts')->href('?page=posts'),
                BreadcrumbItem::make('Edit post')->current(),
            ))
            ->title('Edit post')
            ->description(sprintf('Editing "%s". This form is UI only — nothing is saved.', $post['title']));

        return BlogLayout::wrap('edit-post', $page);
    }

    /**
     * @param array{
     *     id: int, title: string, body: string, status: string, categoryId: int, featured: bool,
     * } $post
     */
    private static function form(array $post): Form
    {
        $categoryOptions = [];

        foreach (Fixtures::categories() as $category) {
            $categoryOptions[$category['slug']] = $category['name'];
        }

        $category = Fixtures::category($post['categoryId']);

        return Form::make(
            FieldGroup::make(
                Label::make('Title')->for('title'),
                Input::make()->id('title')->name('title')->value($post['title'])->required(),
            ),
            FieldGroup::make(
                Label::make('Body')->for('body'),
                Textarea::make($post['body'])->id('body')->name('body')->rows(10),
            ),
            Row::make(
                FieldGroup::make(
                    Label::make('Status')->for('status'),
                    Select::make()->id('status')->name('status')->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                    ])->value($post['status']),
                ),
                FieldGroup::make(
                    Label::make('Category')->for('category'),
                    Select::make()->id('category')->name('category')->options($categoryOptions)->value($category['slug'] ?? null),
                ),
            )->gap(6),
            FieldGroup::make(
                Checkbox::make()->id('featured')->name('featured')->label('Feature this post on the homepage')->checked($post['featured']),
            ),
            Row::make(
                Button::make('Save changes')->type('submit')->primary(),
                Button::make('Cancel')->type('button')->secondary(),
            )->gap(3),
        )->method('POST')->action('#');
    }
}
