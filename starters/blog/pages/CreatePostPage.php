<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
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
 * New post form. Render-only — submitting posts back to `#`, nothing is
 * persisted (there is no database in this starter).
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
        $page = Page::make(
            InfoCard::make(self::form())->title('Post details'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Posts')->href('?page=posts'),
                BreadcrumbItem::make('New post')->current(),
            ))
            ->title('New post')
            ->description('Draft a new post. This form is UI only — nothing is saved.');

        return BlogLayout::wrap('create-post', $page);
    }

    private static function form(): Form
    {
        $categoryOptions = [];

        foreach (Fixtures::categories() as $category) {
            $categoryOptions[$category['slug']] = $category['name'];
        }

        return Form::make(
            FieldGroup::make(
                Label::make('Title')->for('title'),
                Input::make()->id('title')->name('title')->placeholder('Give your post a title')->required(),
            ),
            FieldGroup::make(
                Label::make('Body')->for('body'),
                Textarea::make()->id('body')->name('body')->rows(10)->placeholder('Write your post…'),
            ),
            Row::make(
                FieldGroup::make(
                    Label::make('Status')->for('status'),
                    Select::make()->id('status')->name('status')->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                    ])->value('draft'),
                ),
                FieldGroup::make(
                    Label::make('Category')->for('category'),
                    Select::make()->id('category')->name('category')->placeholder('Choose a category')->options($categoryOptions),
                ),
            )->gap(6),
            FieldGroup::make(
                Checkbox::make()->id('featured')->name('featured')->label('Feature this post on the homepage'),
            ),
            Row::make(
                Button::make('Save post')->type('submit')->primary(),
                Button::make('Cancel')->type('button')->secondary(),
            )->gap(3),
        )->method('POST')->action('#');
    }
}
