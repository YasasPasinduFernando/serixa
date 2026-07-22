<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Component;

/**
 * Escaped code sample for homepage / playground examples.
 */
final class CodeSample extends Component
{
    private function __construct(
        private readonly string $code,
        private readonly string $language = 'php',
    ) {
        parent::__construct();
    }

    public static function make(string $code, string $language = 'php'): self
    {
        return new self($code, $language);
    }

    public function render(): string
    {
        return sprintf(
            '<pre class="code-sample" data-lang="%s"><code>%s</code></pre>',
            $this->escape($this->language),
            $this->escape($this->code),
        );
    }
}
