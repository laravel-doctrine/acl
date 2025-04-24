<?php

use Tests\TestCase;
use Workbench\App\Entities\User;
use Workbench\App\Entities\Role;

class HasPermissionsTest extends TestCase
{
    protected ?User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = entity(User::class)->create();
    }

    public function test_doesnt_have_permission_when_no_roles_and_no_permissions(): void
    {
        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function test_doesnt_have_permission_when_no_roles_with_other_permissions(): void
    {
        $this->user->setPermissions([
            'create.page',
        ]);

        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function test_doesnt_have_permission_with_roles_and_other_permissions(): void
    {
        $this->user->setRoles([
            entity(Role::class)->make(),
        ]);

        $this->user->setPermissions([
            'create.page',
        ]);

        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function test_doesnt_have_permission_with_roles_with_other_permissions_and_other_permissions(): void
    {
        $role = entity(Role::class)->make();
        $role->setPermissions([
            'create.page',
        ]);

        $this->user->setRoles([
            $role,
        ]);

        $this->user->setPermissions([
            'create.page',
        ]);

        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function test_doesnt_have_permission_with_permission_but_no_other_permissions(): void
    {
        $this->user->setPermissions([
            'create.page',
        ]);

        $this->assertFalse($this->user->hasPermissionTo(['create.post', 'create.comment']));
    }

    public function test_doesnt_have_permission_with_permission_but_not_all_other_permissions(): void
    {
        $this->user->setPermissions([
            'create.page',
            'create.post'
        ]);

        $this->assertFalse($this->user->hasPermissionTo(['create.post', 'create.page', 'create.comment'], true));
    }

    public function test_user_has_permission_when_no_roles_but_has_the_permission(): void
    {
        $this->user->setPermissions([
            'create.post',
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_with_roles_but_has_the_permission(): void
    {
        $this->user->setRoles([
            entity(Role::class)->make(),
        ]);

        $this->user->setPermissions([
            'create.post',
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_role_has_permission(): void
    {
        $role = entity(Role::class)->make();
        $role->setPermissions([
            'create.post',
        ]);

        $this->user->setRoles([
            $role,
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_one_role_has_permission(): void
    {
        $role = entity(Role::class)->make();
        $role->setPermissions([
            'create.post',
        ]);

        $this->user->setRoles([
            entity(Role::class)->make(),
            $role,
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_can_check_if_has_permission_with_permission_objects(): void
    {
        $this->user->setPermissions([
            new \LaravelDoctrine\ACL\Permissions\Permission('create.post'),
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_role_has_permission_with_object(): void
    {
        $role = entity(Role::class)->create();
        $role->setPermissions([
            new \LaravelDoctrine\ACL\Permissions\Permission('create.post'),
        ]);

        $this->user->setRoles([
            $role,
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_has_permission_with_permission_but_not_all_other_permissions(): void
    {
        $this->user->setPermissions([
            'create.page',
        ]);

        $this->assertTrue($this->user->hasPermissionTo(['create.post', 'create.page', 'create.comment']));
    }

    public function test_has_permission_and_all_permissions()
    {
        $this->user->setPermissions([
            'create.page',
            'create.post'
        ]);

        $this->assertTrue($this->user->hasPermissionTo(['create.post', 'create.page'], true));
    }

    public function test_user_has_permission_by_object()
    {
        $this->user->setPermissions(['test.test']);

        $this->assertTrue($this->user->hasPermissionTo(new \LaravelDoctrine\ACL\Permissions\Permission('test.test')));
    }

    public function test_user_has_object_permission_by_object(): void
    {
        $this->user->setPermissions([new \LaravelDoctrine\ACL\Permissions\Permission('test.test')]);

        $this->assertTrue($this->user->hasPermissionTo(new \LaravelDoctrine\ACL\Permissions\Permission('test.test')));
    }
}
