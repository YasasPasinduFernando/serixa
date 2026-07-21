<?php

declare(strict_types=1);

use App\Layouts\CrmLayout;
use App\Pages\CalendarPage;
use App\Pages\CompaniesPage;
use App\Pages\CustomersPage;
use App\Pages\DashboardPage;
use App\Pages\InvoicesPage;
use App\Pages\LoginPage;
use App\Pages\ProfilePage;
use App\Pages\SettingsPage;
use App\Pages\TasksPage;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = strtolower((string) ($_GET['page'] ?? 'dashboard'));

$document = match ($page) {
    'customers' => CustomersPage::document(),
    'companies' => CompaniesPage::document(),
    'invoices' => InvoicesPage::document(),
    'tasks' => TasksPage::document(),
    'calendar' => CalendarPage::document(),
    'profile' => ProfilePage::document(),
    'settings' => SettingsPage::document(),
    'login' => LoginPage::document(),
    default => DashboardPage::document(),
};

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render(CrmLayout::wrap($document));
