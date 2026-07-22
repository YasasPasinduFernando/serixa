<?php

declare(strict_types=1);

/**
 * One-shot generator for starters/crm application sources.
 * Run from repo root: php starters/crm/bin/generate-app.php
 */

$root = dirname(__DIR__);

$write = static function (string $rel, string $contents) use ($root): void {
    $path = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    $dir = dirname($path);
    if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
        throw new RuntimeException('Cannot mkdir ' . $dir);
    }
    file_put_contents($path, $contents);
    echo "wrote {$rel}\n";
};

$write('composer.json', <<<'JSON'
{
    "name": "serixa/starter-crm",
    "description": "Official SERIXA CRM Starter — sales pipeline demo built with framework, admin, forms, icons, charts, and calendar.",
    "type": "project",
    "license": "MIT",
    "homepage": "https://github.com/YasasPasinduFernando/serixa",
    "support": {
        "issues": "https://github.com/YasasPasinduFernando/serixa/issues",
        "source": "https://github.com/YasasPasinduFernando/serixa",
        "docs": "https://github.com/YasasPasinduFernando/serixa/tree/main/docs"
    },
    "keywords": ["serixa", "php", "ui", "crm", "starter"],
    "authors": [{"name": "SERIXA Contributors", "homepage": "https://github.com/YasasPasinduFernando/serixa"}],
    "require": {
        "php": "^8.2",
        "serixa/framework": "*@dev",
        "serixa/icons": "*@dev",
        "serixa/admin": "*@dev",
        "serixa/forms": "*@dev",
        "serixa/charts": "*@dev",
        "serixa/calendar": "*@dev"
    },
    "require-dev": {
        "phpunit/phpunit": "^11.0",
        "phpstan/phpstan": "^2.0",
        "friendsofphp/php-cs-fixer": "^3.64"
    },
    "autoload": {
        "psr-4": {
            "App\\": ["app/", "components/", "layouts/", "pages/", "routes/"]
        }
    },
    "autoload-dev": {
        "psr-4": {
            "App\\Tests\\": "tests/"
        }
    },
    "repositories": [
        {"type": "path", "url": "../../framework", "options": {"symlink": true}},
        {"type": "path", "url": "../../packages/icons", "options": {"symlink": true}},
        {"type": "path", "url": "../../packages/admin", "options": {"symlink": true}},
        {"type": "path", "url": "../../packages/forms", "options": {"symlink": true}},
        {"type": "path", "url": "../../packages/charts", "options": {"symlink": true}},
        {"type": "path", "url": "../../packages/calendar", "options": {"symlink": true}}
    ],
    "scripts": {
        "test": "phpunit",
        "phpstan": "phpstan analyse --memory-limit=512M",
        "cs-check": "php-cs-fixer fix --dry-run --diff",
        "cs-fix": "php-cs-fixer fix",
        "qa": ["@test", "@phpstan", "@cs-check"],
        "start": "php -S localhost:8000 -t public",
        "build": "php -r \"require 'vendor/autoload.php'; echo Serixa\\Styling\\StyleSheet::build(__DIR__)['bytes'], PHP_EOL;\""
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
JSON);

$write('public/index.php', <<<'PHP'
<?php

declare(strict_types=1);

use App\Web;
use Serixa\Admin\Theme\AdminTheme;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

require dirname(__DIR__) . '/vendor/autoload.php';

ThemeManager::reset();
IconRegistry::reset();
ThemeManager::set(new AdminTheme(new DefaultTheme()));

$page = is_string($_GET['page'] ?? null) ? $_GET['page'] : 'dashboard';

if (! Web::isKnown($page)) {
    http_response_code(404);
}

$document = Web::document($page, $_GET);

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render($document), PHP_EOL;
PHP);

$write('app/DocumentFactory.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace App;

use Serixa\Calendar\Runtime\CalendarRuntime;
use Serixa\Charts\Runtime\ChartRuntime;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;
use Serixa\Styling\StyleSheet;

/**
 * Builds a fully-configured {@see Document}: SEO title, offline CSS, runtimes.
 */
final class DocumentFactory
{
    private const SITE_NAME = 'SERIXA CRM';

    public static function make(string $title, WidgetInterface $body): Document
    {
        $document = Document::make()
            ->title($title === '' ? self::SITE_NAME : sprintf('%s · %s', $title, self::SITE_NAME))
            ->description('Official SERIXA CRM Starter — sales pipeline UI demo with fixtures only (no database).')
            ->themeColor('#0f766e')
            ->body($body);

        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets());
        ChartRuntime::register($document->assets(), '/assets/charts/charts.js');
        CalendarRuntime::register($document->assets(), '/assets/calendar/calendar.js');

        return $document;
    }
}
PHP);

$write('app/Support/DemoCsrf.php', <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Support;

use Serixa\Forms\Contracts\CsrfProvider;

/** Demo-only CSRF provider for starter validation UIs. */
final class DemoCsrf implements CsrfProvider
{
    public function token(): string
    {
        return 'serixa-crm-demo-csrf-token';
    }

    public function fieldName(): string
    {
        return '_token';
    }

    public function validate(?string $token): bool
    {
        return $token === $this->token();
    }
}
PHP);

echo "core stubs written\n";
