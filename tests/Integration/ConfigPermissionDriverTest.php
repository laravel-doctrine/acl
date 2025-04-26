<?php

declare(strict_types=1);

namespace Tests\Integration;

use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Application;
use Tests\TestCase;
use Workbench\App\Entities\UserJsonPermissions;

use function entity;

class ConfigPermissionDriverTest extends TestCase
{
    /**
     * @param Application $app
     *
     * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('acl.permissions.driver', 'config');
    }

    public function testPermissionsAreLoadedFromConfig(): void
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
