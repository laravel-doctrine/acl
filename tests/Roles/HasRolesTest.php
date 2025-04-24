<?php

use Tests\TestCase;
use Workbench\App\Entities\User;
use Workbench\App\Entities\Role;

class HasRolesTest extends TestCase
{
    protected ?User $user;
    protected ?Role $admin;
    protected ?Role $extraRole1;
    protected ?Role $extraRole2;

    public function setUp(): void
    {
        parent::setUp();
        $this->user       = entity(User::class)->create();
        $this->admin      = entity(Role::class, 'admin')->create();
        $this->extraRole1 = entity(Role::class)->create([
            'name' => 'extraRole1',
        ]);
        $this->extraRole2 = entity(Role::class)->create([
            'name' => 'extraRole2',
        ]);
    }

    public function test_roles_and_role_names_combinations(): void
    {
        // Initial state: user has no roles
        $this->assertFalse($this->user->hasRole($this->admin));
        $this->assertFalse($this->user->hasRoleByName('admin'));

        // User has a different role (not admin)
        $this->user->setRoles([
            entity(Role::class, 'user')->create()
        ]);
        $this->assertFalse($this->user->hasRole($this->admin));
        $this->assertFalse($this->user->hasRoleByName('admin'));

        // User has only admin
        $this->user->setRoles([
            $this->admin
        ]);
        $this->assertFalse($this->user->hasRole([$this->extraRole1, $this->extraRole2]));
        $this->assertFalse($this->user->hasRoleByName(['extraRole1', 'extraRole2']));
        $this->assertTrue($this->user->hasRole($this->admin));
        $this->assertTrue($this->user->hasRoleByName('admin'));

        // User has admin and extraRole1
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1
        ]);
        $this->assertFalse($this->user->hasRole([
            $this->admin, $this->extraRole1, $this->extraRole2
        ], true));
        $this->assertFalse($this->user->hasRoleByName([
            'admin', 'extraRole1', 'extraRole2'
        ], true));

        // User has admin, extraRole1, extraRole2
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2
        ]);
        $this->assertTrue($this->user->hasRole([
            $this->admin, $this->extraRole1
        ]));
        $this->assertTrue($this->user->hasRole([
            $this->admin, $this->extraRole1, $this->extraRole2
        ], true));
        $this->assertTrue($this->user->hasRoleByName([
            'admin', 'extraRole1'
        ]));
        $this->assertTrue($this->user->hasRoleByName([
            'admin', 'extraRole1', 'extraRole2'
        ], true));
    }
}
