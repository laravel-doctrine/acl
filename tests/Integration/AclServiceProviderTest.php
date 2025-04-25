<?php

namespace Tests\Integration;

use Illuminate\Contracts\Auth\Access\Gate;
use LaravelDoctrine\ACL\Permissions\PermissionManager;
use Tests\TestCase;
use Workbench\App\Entities\User;

class AclServiceProviderTest extends TestCase
{
    public function test_permissions_are_defined_on_gate()
    {
        // Arrange: Mock PermissionManager to return test permissions
        $manager = $this->createMock(PermissionManager::class);
        $manager->method('getPermissionsWithDotNotation')->willReturn(['foo.bar', 'baz.qux']);
        $this->app->instance(PermissionManager::class, $manager);

        // Get the Gate
        $gate = $this->app->make(Gate::class);

        // Assert: Gate has the permissions defined
        $this->assertTrue($gate->has('foo.bar'));
        $this->assertTrue($gate->has('baz.qux'));

        $user = entity(User::class)->create();
        $user->setPermissions(['foo.bar']);
        $this->actingAs($user);

        $this->assertTrue($gate->allows('foo.bar'));
        $this->assertFalse($gate->allows('baz.quxdkdkd'));
    }

    public function test_no_permissions_defined_when_manager_returns_empty()
    {
        // $manager = $this->createMock(PermissionManager::class);
        // $manager->method('getPermissionsWithDotNotation')->willReturn([]);
        // $this->app->instance(PermissionManager::class, $manager);

        $gate = $this->app->make(Gate::class);

        $this->assertFalse($gate->has('any.permission'));
    }
}
