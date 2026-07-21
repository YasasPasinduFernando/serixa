<?php

declare(strict_types=1);

use App\Layouts\AdminLayout;
use App\Pages\AnalyticsPage;
use App\Pages\CrudPage;
use App\Pages\DashboardPage;
use App\Pages\LoginPage;
use App\Pages\NotFoundPage;
use App\Pages\NotificationsPage;
use App\Pages\ProfilePage;
use App\Pages\RolesPage;
use App\Pages\SettingsPage;
use App\Pages\UsersPage;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = strtolower((string) ($_GET['page'] ?? 'dashboard'));

$document = match ($page) {
    'dashboard' => DashboardPage::document(),
    'analytics' => AnalyticsPage::document(),
    'users' => UsersPage::document(),
    'roles' => RolesPage::document(),
    'crud' => CrudPage::document(),
    'settings' => SettingsPage::document(),
    'profile' => ProfilePage::document(),
    'notifications' => NotificationsPage::document(),
    'login' => LoginPage::document(),
    default => NotFoundPage::document(),
};

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render(AdminLayout::wrap($document));
