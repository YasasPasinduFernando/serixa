<?php

declare(strict_types=1);

namespace Serixa\Forms\Components;

use Serixa\Component\Component;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\FormState;
use Serixa\Forms\Support\ResolvesFormsTheme;

/**
 * Form-wide validation error summary with {@code role=alert}.
 */
final class ValidationSummary extends Component implements FormsWidgetInterface
{
    use ResolvesFormsTheme;

    /** @var list<string> */
    private array $messages;

    private string $title = 'Please fix the following errors';

    /**
     * @param list<string> $messages
     */
    private function __construct(array $messages)
    {
        parent::__construct();
        $this->messages = array_values($messages);
    }

    /**
     * @param ErrorsBag|FormState|list<string> $source
     */
    public static function make(ErrorsBag|FormState|array $source): self
    {
        return new self(self::extractMessages($source));
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function render(): string
    {
        if ($this->messages === []) {
            return '';
        }

        $items = '';

        foreach ($this->messages as $message) {
            $items .= sprintf('<li>%s</li>', $this->escape($message));
        }

        $titleHtml = sprintf(
            '<p class="font-semibold m-0 mb-2">%s</p>',
            $this->escape($this->title),
        );

        $listHtml = sprintf('<ul class="m-0 pl-5 list-disc">%s</ul>', $items);

        $attrs = $this->buildAttributeString(
            $this->theme()->classes('forms.summary'),
            ['role' => 'alert'],
        );

        return sprintf('<div%s>%s%s</div>', $attrs, $titleHtml, $listHtml);
    }

    /**
     * @param ErrorsBag|FormState|list<string> $source
     * @return list<string>
     */
    private static function extractMessages(ErrorsBag|FormState|array $source): array
    {
        if ($source instanceof FormState) {
            $source = $source->errors();
        }

        if ($source instanceof ErrorsBag) {
            $flat = [];

            foreach ($source->all() as $messages) {
                foreach ($messages as $message) {
                    $flat[] = $message;
                }
            }

            return $flat;
        }

        $flat = [];

        foreach ($source as $message) {
            if (is_string($message) && $message !== '') {
                $flat[] = $message;
            }
        }

        return $flat;
    }
}
