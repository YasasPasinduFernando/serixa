<?php

declare(strict_types=1);

namespace NovaPHP\Contracts;

use NovaPHP\Document\Asset\AssetManager;
use NovaPHP\Document\Seo\SeoManager;

/**
 * A full HTML document (not a fragment).
 */
interface DocumentInterface
{
    public function seo(): SeoManager;

    public function assets(): AssetManager;

    public function getLanguage(): string;

    /**
     * @return list<\NovaPHP\Contracts\WidgetInterface|string>
     */
    public function bodyChildren(): array;
}
