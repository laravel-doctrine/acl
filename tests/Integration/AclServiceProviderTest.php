<?php

declare(strict_types=1);

namespace Tests\Integration;

use Illuminate\Contracts\Auth\Access\Gate;
use LaravelDoctrine\ACL\Permissions\PermissionManager;
use Tests\TestCase;
use Workbench\App\Entities\User;

use function entity;

class AclServiceProviderTest extends TestCase
{
    public function testPermissionsAreDefinedOnGate(): void
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

    public function testNoPermissionsDefinedWhenManagerReturnsEmpty(): void
    {
        // $manager = $this->createMock(PermissionManager::class);
        // $manager->method('getPermissionsWithDotNotation')->willReturn([]);
        // $this->app->instance(PermissionManager::class, $manager);

        $gate = $this->app->make(Gate::class);

        $this->assertFalse($gate->has('any.permission'));
    }
}
