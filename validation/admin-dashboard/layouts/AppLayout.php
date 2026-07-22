<?php

declare(strict_types=1);

namespace App\Layouts;

use Serixa\Component\Container;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Styling\StyleSheet;
use Serixa\Runtime\ClientRuntime;

/**
 * Shared application chrome for admin-dashboard.
 */
final class AppLayout
{
    public static function wrap(Document $document): Document
    {
        StyleSheet::register($document->assets());

        ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');

        return $document;
    }

    public static function titleBar(string $title): WidgetInterface
    {
        return Container::make(
            Text::make($title)->as('h1')->size('2xl')->weight('bold'),
        );
    }
}
