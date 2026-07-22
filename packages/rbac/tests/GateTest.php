<?php

declare(strict_types=1);

namespace Serixa\Rbac\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Rbac\Exceptions\AuthorizationException;
use Serixa\Rbac\Permission;
use Serixa\Rbac\Rbac;
use Serixa\Rbac\Role;
use Serixa\Rbac\SimpleSubject;

final class GateTest extends TestCase
{
    public function testRoleGrantsPermission(): void
    {
        $rbac = Rbac::memory();
        $rbac->definePermission('users.view');
        $rbac->defineRole('editor', 'Editor', ['users.view']);
        $user = SimpleSubject::make('u1');
        $rbac->assign($user, 'editor');

        self::assertTrue($rbac->can($user, 'users.view'));
        self::assertTrue($rbac->cannot($user, 'users.delete'));
    }

    public function testDirectGrant(): void
    {
        $rbac = Rbac::memory();
        $rbac->grant('u2', 'reports.export');

        self::assertTrue($rbac->can('u2', 'reports.export'));
    }

    public function testAuthorizeThrows(): void
    {
        $rbac = Rbac::memory();
        $this->expectException(AuthorizationException::class);
        $rbac->authorize('u3', 'admin.access');
    }

    public function testAbilitiesMergeRolesAndDirect(): void
    {
        $rbac = Rbac::memory();
        $rbac->defineRole('staff', null, ['a.read', 'a.write']);
        $rbac->assign('u4', 'staff');
        $rbac->grant('u4', 'a.export');

        $abilities = $rbac->gate()->abilities('u4');
        sort($abilities);
        self::assertSame(['a.export', 'a.read', 'a.write'], $abilities);
    }

    public function testEmptyPermissionNameRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Permission::make('');
    }

    public function testEmptyRoleNameRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Role::make('');
    }

    public function testRoleWithWithoutPermission(): void
    {
        $role = Role::make('ops', null, ['x'])->withPermission('y')->withoutPermission('x');
        self::assertFalse($role->hasPermission('x'));
        self::assertTrue($role->hasPermission('y'));
    }

    public function testMemoryStoresIsolate(): void
    {
        $a = Rbac::memory();
        $b = Rbac::memory();
        $a->defineRole('admin', null, ['*']);
        self::assertFalse($b->roles()->has('admin'));
    }
}
