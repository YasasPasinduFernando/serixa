<?php

declare(strict_types=1);

use Serixa\Rendering\Renderer;
use SerixaDemo\Pages\CrudPage;
use SerixaDemo\Pages\DashboardPage;
use SerixaDemo\Pages\LoginPage;
use SerixaDemo\Pages\ProfilePage;
use SerixaDemo\Pages\SettingsPage;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = strtolower((string) ($_GET['page'] ?? 'login'));

$document = match ($page) {
    'dashboard' => DashboardPage::document(),
    'crud', 'users' => CrudPage::document(),
    'settings' => SettingsPage::document(),
    'profile' => ProfilePage::document(),
    default => LoginPage::document(),
};

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render($document);
