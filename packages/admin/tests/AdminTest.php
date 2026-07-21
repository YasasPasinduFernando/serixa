<?php

declare(strict_types=1);

namespace Serixa\Admin\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Cards\MetricGrid;
use Serixa\Admin\Cards\Statistic;
use Serixa\Admin\Components\AvatarGroup;
use Serixa\Admin\Components\DescriptionItem;
use Serixa\Admin\Components\DescriptionList;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\Components\UserChip;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Exceptions\AdminException;
use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\AppShell;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\PageContainer;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarGroup;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Page\PageActions;
use Serixa\Admin\Page\PageHeader;
use Serixa\Admin\Tables\BulkActions;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\ImportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Tables\TableToolbar;
use Serixa\Admin\Theme\AdminTheme;
use Serixa\Admin\Toolbar\Toolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Admin\Toolbar\ToolbarGroup;
use Serixa\Admin\UserMenu\UserMenu;
use Serixa\Component\Avatar;
use Serixa\Component\Button;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Rendering\Renderer;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

final class AdminTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        ThemeManager::reset();
        IconRegistry::reset();
        $this->renderer = new Renderer();
    }

    public function testAppShellRendersScaffoldStructure(): void
    {
        $html = $this->renderer->render(
            AppShell::make()
                ->topBar(TopBar::make()->title('Acme Admin'))
                ->sidebar(Sidebar::make()->child(SidebarItem::make('Dashboard')->href('/admin')))
                ->content(MainContent::make('Body'))
                ->footer(FooterBar::make('© Acme')),
        );

        $this->assertStringContainsString('<header', $html);
        $this->assertStringContainsString('<aside', $html);
        $this->assertStringContainsString('<footer', $html);
        $this->assertStringContainsString('Acme Admin', $html);
        $this->assertStringContainsString('Body', $html);
    }

    public function testSidebarItemActiveGetsAriaCurrentPage(): void
    {
        $html = $this->renderer->render(SidebarItem::make('Dashboard')->href('/admin')->active());

        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function testSidebarItemInactiveHasNoAriaCurrent(): void
    {
        $html = $this->renderer->render(SidebarItem::make('Dashboard')->href('/admin'));

        $this->assertStringNotContainsString('aria-current', $html);
    }

    public function testBreadcrumbIsNavLandmark(): void
    {
        $html = $this->renderer->render(
            Breadcrumb::make(
                BreadcrumbItem::make('Admin')->href('/admin'),
                BreadcrumbItem::make('Users')->current(),
            ),
        );

        $this->assertStringContainsString('<nav', $html);
        $this->assertStringContainsString('aria-label="Breadcrumb"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function testToolbarHasRoleToolbar(): void
    {
        $html = $this->renderer->render(Toolbar::make(ToolbarGroup::make(ToolbarAction::make('Create'))));

        $this->assertStringContainsString('role="toolbar"', $html);
    }

    public function testToolbarActionRendersLinkWhenHrefGiven(): void
    {
        $html = $this->renderer->render(ToolbarAction::make('View')->href('/admin/users/1'));

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/admin/users/1"', $html);
    }

    public function testMetricCardRendersHeadingAndValue(): void
    {
        $html = $this->renderer->render(
            MetricCard::make()->title('Active users')->value('12,480')->trend('+4.2%', 'up')->icon('users'),
        );

        $this->assertStringContainsString('Active users', $html);
        $this->assertStringContainsString('12,480', $html);
        $this->assertStringContainsString('+4.2%', $html);
        $this->assertStringContainsString('<svg', $html);
    }

    public function testMetricGridRendersEachCard(): void
    {
        $html = $this->renderer->render(
            MetricGrid::make(
                MetricCard::make()->title('Users')->value('1,204'),
                MetricCard::make()->title('Revenue')->value('$48k'),
            ),
        );

        $this->assertStringContainsString('Users', $html);
        $this->assertStringContainsString('Revenue', $html);
    }

    public function testIconsAppearAsInlineSvg(): void
    {
        $html = $this->renderer->render(SidebarItem::make('Dashboard')->icon('home')->href('/admin'));

        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('xmlns="http://www.w3.org/2000/svg"', $html);
    }

    public function testThemeAdminClassesArePresent(): void
    {
        $theme = new AdminTheme(new DefaultTheme());

        $classes = $theme->classes('admin.metricCard');

        $this->assertNotEmpty($classes);
        $this->assertContains('flex', $classes);
    }

    public function testThemeDelegatesNonAdminComponentsToInnerTheme(): void
    {
        $theme = new AdminTheme(new DefaultTheme());

        $this->assertSame((new DefaultTheme())->classes('button', ['variant' => 'primary']), $theme->classes('button', ['variant' => 'primary']));
    }

    public function testThemeWrapsAutomaticallyWhenResolvingAdminWidgets(): void
    {
        $html = $this->renderer->render(StatusBadge::make('Active')->status('success'));

        $this->assertStringContainsString('bg-emerald-100', $html);
    }

    public function testTableToolbarRenders(): void
    {
        $html = $this->renderer->render(TableToolbar::make()->actions(ExportButton::make(), ImportButton::make()));

        $this->assertStringContainsString('Export', $html);
        $this->assertStringContainsString('Import', $html);
    }

    public function testSearchToolbarRenders(): void
    {
        $html = $this->renderer->render(SearchToolbar::make()->placeholder('Search users…'));

        $this->assertStringContainsString('type="search"', $html);
        $this->assertStringContainsString('Search users…', $html);
    }

    public function testUnknownOptionsThrow(): void
    {
        $this->expectException(AdminException::class);
        StatusBadge::make('Broken')->status('not-a-real-status');
    }

    public function testUnknownAdminThemeComponentThrows(): void
    {
        $theme = new AdminTheme(new DefaultTheme());

        $this->expectException(\InvalidArgumentException::class);
        $theme->classes('admin.does-not-exist');
    }

    public function testCompositionFullShellWithSidebarTopBarAndContent(): void
    {
        $html = $this->renderer->render(
            AdminLayout::shell()
                ->topBar(
                    TopBar::make()
                        ->title('Acme Admin')
                        ->actions(UserMenu::make('Ada Lovelace')->item('Profile', '/profile')->divider()->item('Sign out', '/logout', true)),
                )
                ->sidebar(
                    Sidebar::make()->children(
                        SidebarGroup::make()->label('Overview')->items(
                            SidebarItem::make('Dashboard')->icon('home')->href('/admin')->active(),
                            SidebarItem::make('Users')->icon('users')->href('/admin/users')->badge('12'),
                        ),
                    ),
                )
                ->content(
                    MainContent::make(
                        PageContainer::make(
                            Page::make(
                                MetricGrid::make(MetricCard::make()->title('Users')->value('1,204')),
                            )
                                ->title('Dashboard')
                                ->description('Overview of your workspace')
                                ->breadcrumb(Breadcrumb::make(BreadcrumbItem::make('Dashboard')->current()))
                                ->actions(PageActions::make(Toolbar::make(ToolbarAction::make('Create')->primary()))),
                        ),
                    ),
                )
                ->footer(FooterBar::make('© 2026 Acme Inc.')),
        );

        $this->assertStringContainsString('Acme Admin', $html);
        $this->assertStringContainsString('Dashboard', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('1,204', $html);
        $this->assertStringContainsString('<nav', $html);
        $this->assertStringContainsString('role="toolbar"', $html);
    }

    public function testInfoCardRendersTitleAndBody(): void
    {
        $html = $this->renderer->render(InfoCard::make('Body text')->title('Account'));

        $this->assertStringContainsString('Account', $html);
        $this->assertStringContainsString('Body text', $html);
    }

    public function testStatisticRendersLabelAndValue(): void
    {
        $html = $this->renderer->render(Statistic::make('Open invoices', '12'));

        $this->assertStringContainsString('Open invoices', $html);
        $this->assertStringContainsString('12', $html);
    }

    public function testAvatarGroupRendersOverflowBadge(): void
    {
        $html = $this->renderer->render(
            AvatarGroup::make(
                Avatar::make()->initials('AL'),
                Avatar::make()->initials('BC'),
                Avatar::make()->initials('CD'),
            )->max(2),
        );

        $this->assertStringContainsString('+1', $html);
    }

    public function testUserChipRendersNameAndAvatar(): void
    {
        $html = $this->renderer->render(UserChip::make('Ada Lovelace')->subtitle('ada@example.com'));

        $this->assertStringContainsString('Ada Lovelace', $html);
        $this->assertStringContainsString('ada@example.com', $html);
    }

    public function testDescriptionListRendersTermsAndDescriptions(): void
    {
        $html = $this->renderer->render(
            DescriptionList::make(
                DescriptionItem::make('Plan', 'Pro'),
                DescriptionItem::make('Seats', '25'),
            ),
        );

        $this->assertStringContainsString('<dt', $html);
        $this->assertStringContainsString('<dd', $html);
        $this->assertStringContainsString('Plan', $html);
        $this->assertStringContainsString('Pro', $html);
    }

    public function testEmptyStateWrapsFrameworkEmptyStateWithIcon(): void
    {
        $html = $this->renderer->render(
            EmptyState::make()->icon('inbox')->title('No results')->description('Try a different search.'),
        );

        $this->assertStringContainsString('No results', $html);
        $this->assertStringContainsString('<svg', $html);
    }

    public function testBulkActionsRendersSummaryAndChildren(): void
    {
        $html = $this->renderer->render(BulkActions::make(Button::make('Delete')->danger())->summary('3 selected'));

        $this->assertStringContainsString('3 selected', $html);
        $this->assertStringContainsString('Delete', $html);
    }

    public function testRowActionsRendersLinks(): void
    {
        $html = $this->renderer->render(RowActions::make()->link('Edit', '/edit')->link('Delete', '/delete'));

        $this->assertStringContainsString('href="/edit"', $html);
        $this->assertStringContainsString('href="/delete"', $html);
    }

    public function testColumnVisibilityRendersDetailsElement(): void
    {
        $html = $this->renderer->render(ColumnVisibility::make()->column('Name')->column('Created at', false));

        $this->assertStringContainsString('<details', $html);
        $this->assertStringContainsString('<summary', $html);
        $this->assertStringContainsString('Name', $html);
    }

    public function testTableFiltersRendersChildren(): void
    {
        $html = $this->renderer->render(TableFilters::make(Button::make('Status: Active')));

        $this->assertStringContainsString('Status: Active', $html);
    }

    public function testPageHeaderRendersActionsAndDescription(): void
    {
        $html = $this->renderer->render(
            PageHeader::make()->title('Users')->description('Manage accounts')->actions(Button::make('Create')->primary()),
        );

        $this->assertStringContainsString('Users', $html);
        $this->assertStringContainsString('Manage accounts', $html);
        $this->assertStringContainsString('Create', $html);
    }
}
