<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\Contracts\DocumentInterface;
use Serixa\Contracts\WidgetInterface;
use Serixa\DevTools\Contracts\InspectorInterface;
use Serixa\Document\Document;

final class DocumentTreeInspector implements InspectorInterface
{
    public function name(): string
    {
        return 'document_tree';
    }

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array
    {
        if (! $subject instanceof DocumentInterface) {
            return ['document' => null];
        }

        $title = null;

        if ($subject instanceof Document) {
            $title = $subject->seo()->getTitle();
        }

        $bodyChildren = [];

        foreach ($subject->bodyChildren() as $child) {
            $bodyChildren[] = $child instanceof WidgetInterface ? $child::class : '(text)';
        }

        return [
            'document' => [
                'title' => $title,
                'language' => $subject->getLanguage(),
                'bodyChildren' => $bodyChildren,
            ],
        ];
    }
}
