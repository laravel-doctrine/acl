<?php

declare(strict_types=1);

namespace Tests\Integration;

use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Tests\TestCase;
use Workbench\App\Entities\UserJsonPermissions;

class ConfigPermissionDriverTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('acl.permissions.driver', 'config');
    }

    public function test_permissions_are_loaded_from_config(): void
    {
        $em = $this->app->make(EntityManager::class);

        $user = entity(UserJsonPermissions::class)->create();
        $user->setPermissions(['role.attach']);

        $em->persist($user);
        $em->flush();

        $this->actingAs($user);

        $gate = $this->app->make(Gate::class);

        $this->assertTrue($gate->allows('role.attach'));
        $this->assertFalse($gate->allows('no_permission'));
    }
}
