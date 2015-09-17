<?php

use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Contracts\Role;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Roles\HasRoles;

class HasPermissionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UserMock
     */
    protected $user;

    /**
     * @var UserMock
     */
    protected $userWithRoles;

    protected function setUp()
    {
        $this->user          = new UserMock;
        $this->userWithRoles = new UserMockWithRoles;
    }

    public function test_doesnt_have_permission_when_no_roles_and_no_permissions()
    {
        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function test_doesnt_have_permission_when_no_roles_with_other_permissions()
    {
        $this->user->setPermissions([
            'create.page',
        ]);

        $this->assertFalse($this->user->hasPermissionTo('create.post'));
    }

    public function test_doesnt_have_permission_with_roles_and_other_permissions()
    {
        $this->userWithRoles->setRoles([
            new RoleMock,
        ]);

        $this->userWithRoles->setPermissions([
            'create.page',
        ]);

        $this->assertFalse($this->userWithRoles->hasPermissionTo('create.post'));
    }

    public function test_doesnt_have_permission_with_roles_with_other_permissions_and_other_permissions()
    {
        $role = new RoleMock;
        $role->setPermissions([
            'create.page',
        ]);

        $this->userWithRoles->setRoles([
            $role,
        ]);

        $this->userWithRoles->setPermissions([
            'create.page',
        ]);

        $this->assertFalse($this->userWithRoles->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_no_roles_but_has_the_permission()
    {
        $this->user->setPermissions([
            'create.post',
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_with_roles_but_has_the_permission()
    {
        $this->userWithRoles->setRoles([
            new RoleMock,
        ]);

        $this->userWithRoles->setPermissions([
            'create.post',
        ]);

        $this->assertTrue($this->userWithRoles->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_role_has_permission()
    {
        $role = new RoleMock;
        $role->setPermissions([
            'create.post',
        ]);

        $this->userWithRoles->setRoles([
            $role,
        ]);

        $this->assertTrue($this->userWithRoles->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_one_role_has_permission()
    {
        $role = new RoleMock;
        $role->setPermissions([
            'create.post',
        ]);

        $this->userWithRoles->setRoles([
            new RoleMock,
            $role,
        ]);

        $this->assertTrue($this->userWithRoles->hasPermissionTo('create.post'));
    }

    public function test_can_check_if_has_permission_with_permission_objects()
    {
        $this->user->setPermissions([
            new \LaravelDoctrine\ACL\Permissions\Permission('create.post'),
        ]);

        $this->assertTrue($this->user->hasPermissionTo('create.post'));
    }

    public function test_user_has_permission_when_role_has_permission_with_object()
    {
        $role = new RoleMock;
        $role->setPermissions([
            new \LaravelDoctrine\ACL\Permissions\Permission('create.post'),
        ]);

        $this->userWithRoles->setRoles([
            $role,
        ]);

        $this->assertTrue($this->userWithRoles->hasPermissionTo('create.post'));
    }
}

class UserMock implements HasPermissionsContract
{
    use HasPermissions;

    protected $permissions = [];

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }
}

class UserMockWithRoles implements HasPermissionsContract, HasRolesContract
{
    use HasPermissions, HasRoles;

    protected $permissions = [];

    protected $roles = [];

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}

class RoleMock implements Role
{
    use HasPermissions;

    protected $permissions = [];

    protected $roles = [];

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Admin';
    }
}
