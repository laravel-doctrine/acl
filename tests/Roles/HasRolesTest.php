<?php

use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Contracts\Role;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Roles\HasRoles;

class HasRolesTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var UserMock2
     */
    protected $user;

    /**
     * @var RoleMock2
     */
    protected $admin;
    /**
     * @var RoleMock2
     */
    protected $extraRole1;
    /**
     * @var RoleMock2
     */
    protected $extraRole2;

    protected function setUp(): void
    {
        $this->user       = new UserMock2;
        $this->admin      = new RoleMock2('admin');
        $this->extraRole1 = new RoleMock2('extraRole1');
        $this->extraRole2 = new RoleMock2('extraRole2');
    }

    public function test_doesnt_have_role_when_no_roles_assigned(): void
    {
        $this->assertFalse($this->user->hasRole($this->admin));
    }

    public function test_doesnt_have_role_by_name_when_no_roles_assigned(): void
    {
        $this->assertFalse($this->user->hasRoleByName('admin'));
    }

    public function test_doesnt_have_role_when_when_other_role_assigned(): void
    {
        $this->user->setRoles([
            new RoleMock2('user'),
        ]);
        $this->assertFalse($this->user->hasRole($this->admin));
    }

    public function test_doesnt_have_any_role_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin
        ]);
        $this->assertFalse($this->user->hasRole([$this->extraRole1, $this->extraRole2]));
    }

    public function test_doesnt_have_any_role_by_name_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin
        ]);
        $this->assertFalse($this->user->hasRoleByName(['extraRole1', 'extraRole2']));
    }

    public function test_doesnt_have_all_roles_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1
        ]);
        $this->assertFalse($this->user->hasRole([$this->admin, $this->extraRole1, $this->extraRole2], true));
    }

    public function test_doesnt_have_all_roles_by_name_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1
        ]);
        $this->assertFalse($this->user->hasRoleByName(['admin', 'extraRole1', 'extraRole2'], true));
    }

    public function test_doesnt_have_role_by_name_when_when_other_role_assigned(): void
    {
        $this->user->setRoles([
            new RoleMock2('user'),
        ]);
        $this->assertFalse($this->user->hasRoleByName('admin'));
    }

    public function test_has_role_when_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
        ]);
        $this->assertTrue($this->user->hasRole($this->admin));
    }

    public function test_has_role_by_name_when_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
        ]);
        $this->assertTrue($this->user->hasRoleByName('admin'));
    }

    public function test_has_any_role_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2
        ]);
        $this->assertTrue($this->user->hasRole([$this->admin, $this->extraRole1]));
    }

    public function test_has_all_role_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2
        ]);
        $this->assertTrue($this->user->hasRole([$this->admin, $this->extraRole1, $this->extraRole2], true));
    }

    public function test_has_any_role_by_name_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2
        ]);
        $this->assertTrue($this->user->hasRoleByName(['admin', 'extraRole1']));
    }

    public function test_has_all_role_by_name_when_role_assigned(): void
    {
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2
        ]);
        $this->assertTrue($this->user->hasRoleByName(['admin', 'extraRole1', 'extraRole2'], true));
    }
}

class UserMock2 implements HasRolesContract
{
    use HasRoles;

    protected $roles = [];

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

class RoleMock2 implements Role
{
    use HasPermissions;

    protected $permissions = [];

    protected $roles = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

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
        return $this->name;
    }
}
