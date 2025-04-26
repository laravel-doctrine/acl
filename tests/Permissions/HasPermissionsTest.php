<?php

declare(strict_types=1);

namespace Tests\Permissions;

use LaravelDoctrine\ACL\Permissions\Permission;
use Tests\TestCase;
use Workbench\App\Entities\Role;
use Workbench\App\Entities\User;

use function entity;

class HasPermissionsTest extends TestCase
{
    protected User|null $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = entity(User::class)->create();
    }

    public function testDoesntHavePermissionWhenNoRolesAndNoPermissions(): void
    {
        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function testDoesntHavePermissionWhenNoRolesWithOtherPermissions(): void
    {
        $this->user->setPermissions(['create.page']);

        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function testDoesntHavePermissionWithRolesAndOtherPermissions(): void
    {
        $this->user->setRoles([
            entity(Role::class)->make(),
        ]);

        $this->user->setPermissions(['create.page']);

        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function testDoesntHavePermissionWithRolesWithOtherPermissionsAndOtherPermissions(): void
    {
        $role = entity(Role::class)->make();
        $role->setPermissions(['create.page']);

        $this->user->setRoles([$role]);

        $this->user->setPermissions(['create.page']);

        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function testDoesntHavePermissionWithPermissionButNoOtherPermissions(): void
    {
        $this->user->setPermissions(['create.page']);

        $this->assertFalse($this->user->hasPermissionTo(['create.post', 'create.comment']));
    }

    public function testDoesntHavePermissionWithPermissionButNotAllOtherPermissions(): void
    {
        $this->user->setPermissions([
            'create.page',
            'create.post',
        ]);

        $this->assertFalse($this->user->hasPermissionTo(['create.post', 'create.page', 'create.comment'], true));
    }

    public function testUserHasPermissionWhenNoRolesButHasThePermission(): void
    {
        $this->user->setPermissions(['create.post']);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function testUserHasPermissionWhenWithRolesButHasThePermission(): void
    {
        $this->user->setRoles([
            entity(Role::class)->make(),
        ]);

        $this->user->setPermissions(['create.post']);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function testUserHasPermissionWhenRoleHasPermission(): void
    {
        $role = entity(Role::class)->make();
        $role->setPermissions(['create.post']);

        $this->user->setRoles([$role]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function testUserHasPermissionWhenOneRoleHasPermission(): void
    {
        $role = entity(Role::class)->make();
        $role->setPermissions(['create.post']);

        $this->user->setRoles([
            entity(Role::class)->make(),
            $role,
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function testCanCheckIfHasPermissionWithPermissionObjects(): void
    {
        $this->user->setPermissions([
            new Permission('create.post'),
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function testUserHasPermissionWhenRoleHasPermissionWithObject(): void
    {
        $role = entity(Role::class)->create();
        $role->setPermissions([
            new Permission('create.post'),
        ]);

        $this->user->setRoles([$role]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function testHasPermissionWithPermissionButNotAllOtherPermissions(): void
    {
        $this->user->setPermissions(['create.page']);

        $this->assertTrue($this->user->hasPermissionTo(['create.post', 'create.page', 'create.comment']));
    }

    public function testHasPermissionAndAllPermissions(): void
    {
        $this->user->setPermissions([
            'create.page',
            'create.post',
        ]);

        $this->assertTrue($this->user->hasPermissionTo(['create.post', 'create.page'], true));
    }

    public function testUserHasPermissionByObject(): void
    {
        $this->user->setPermissions(['test.test']);

        $this->assertTrue($this->user->hasPermissionTo(new Permission('test.test')));
    }

    public function testUserHasObjectPermissionByObject(): void
    {
        $this->user->setPermissions([new Permission('test.test')]);

        $this->assertTrue($this->user->hasPermissionTo(new Permission('test.test')));
    }
}
