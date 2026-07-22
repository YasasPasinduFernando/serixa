<?php

declare(strict_types=1);

namespace Serixa\Permissions\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Permissions\PermissionCatalog;
use Serixa\Permissions\PermissionGroup;
use Serixa\Rbac\Rbac;

final class PermissionCatalogTest extends TestCase
{
    public function testRegisterAndMatrix(): void
    {
        $catalog = PermissionCatalog::make()
            ->group('users', 'Users', ['users.view', 'users.edit'])
            ->group('roles', 'Roles', ['roles.manage']);

        self::assertCount(2, $catalog->groups());
        self::assertCount(3, $catalog->matrix());
        self::assertSame(['users.view', 'users.edit', 'roles.manage'], $catalog->allAbilities());
    }

    public function testSyncToRbac(): void
    {
        $rbac = Rbac::memory();
        PermissionCatalog::make()
            ->group('crm', 'CRM', ['leads.view'])
            ->syncTo($rbac);

        self::assertTrue($rbac->permissions()->has('leads.view'));
    }

    public function testGroupWithAbility(): void
    {
        $g = PermissionGroup::make('a', 'A', ['x'])->withAbility('y');
        self::assertSame(['x', 'y'], $g->abilities());
    }
}
