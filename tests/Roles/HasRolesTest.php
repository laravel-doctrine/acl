<?php

declare(strict_types=1);

namespace Tests\Roles;

use Tests\TestCase;
use Workbench\App\Entities\Role;
use Workbench\App\Entities\User;

use function entity;

class HasRolesTest extends TestCase
{
    protected User|null $user;
    protected Role|null $admin;
    protected Role|null $extraRole1;
    protected Role|null $extraRole2;

    public function setUp(): void
    {
        parent::setUp();

        $this->user       = entity(User::class)->create();
        $this->admin      = entity(Role::class, 'admin')->create();
        $this->extraRole1 = entity(Role::class)->create(['name' => 'extraRole1']);
        $this->extraRole2 = entity(Role::class)->create(['name' => 'extraRole2']);
    }

    public function testRolesAndRoleNamesCombinations(): void
    {
        // Initial state: user has no roles
        $this->assertFalse($this->user->hasRole($this->admin));
        $this->assertFalse($this->user->hasRoleByName('admin'));

        // User has a different role (not admin)
        $this->user->setRoles([entity(Role::class, 'user')->create()]);
        $this->assertFalse($this->user->hasRole($this->admin));
        $this->assertFalse($this->user->hasRoleByName('admin'));

        // User has only admin
        $this->user->setRoles([$this->admin]);
        $this->assertFalse($this->user->hasRole([$this->extraRole1, $this->extraRole2]));
        $this->assertFalse($this->user->hasRoleByName(['extraRole1', 'extraRole2']));
        $this->assertTrue($this->user->hasRole($this->admin));
        $this->assertTrue($this->user->hasRoleByName('admin'));

        // User has admin and extraRole1
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1,
        ]);
        $this->assertFalse($this->user->hasRole([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2,
        ], true));
        $this->assertFalse($this->user->hasRoleByName([
            'admin',
            'extraRole1',
            'extraRole2',
        ], true));

        // User has admin, extraRole1, extraRole2
        $this->user->setRoles([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2,
        ]);
        $this->assertTrue($this->user->hasRole([
            $this->admin,
            $this->extraRole1,
        ]));
        $this->assertTrue($this->user->hasRole([
            $this->admin,
            $this->extraRole1,
            $this->extraRole2,
        ], true));
        $this->assertTrue($this->user->hasRoleByName([
            'admin',
            'extraRole1',
        ]));
        $this->assertTrue($this->user->hasRoleByName([
            'admin',
            'extraRole1',
            'extraRole2',
        ], true));
    }
}
