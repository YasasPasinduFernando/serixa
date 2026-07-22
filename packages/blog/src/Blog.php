<?php

declare(strict_types=1);

namespace Serixa\Blog;

use Serixa\Blog\Contracts\BlogStore;
use Serixa\Blog\Memory\MemoryBlogStore;
use Serixa\Cms\Cms;
use Serixa\Cms\ContentBlock;

final class Blog
{
    public function __construct(
        private readonly BlogStore $store,
        private readonly ?Cms $cms = null,
    ) {
    }

    public static function memory(?Cms $cms = null): self
    {
        return new self(new MemoryBlogStore(), $cms);
    }

    public function store(): BlogStore
    {
        return $this->store;
    }

    public function addCategory(string $id, string $slug, string $name): Category
    {
        $category = Category::make($id, $slug, $name);
        $this->store->putCategory($category);

        return $category;
    }

    public function createPost(
        string $id,
        string $slug,
        string $title,
        string $excerpt = '',
        ?string $authorId = null,
    ): Post {
        $post = Post::make($id, $slug, $title, $excerpt, $authorId);
        $this->store->put($post);

        return $post;
    }

    /**
     * Publish post and optionally mirror as a CMS page.
     */
    public function publish(Post $post, bool $mirrorToCms = true): Post
    {
        $post->publish();
        $this->store->put($post);

        if ($mirrorToCms && $this->cms !== null) {
            $this->cms->store()->put($post->toCmsPage());
        }

        return $post;
    }

    public function writeBody(Post $post, string $markdown): Post
    {
        $post->addBlock(ContentBlock::make('body', 'markdown', $markdown));
        $this->store->put($post);

        return $post;
    }
}
