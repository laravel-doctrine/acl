<?php

declare(strict_types=1);

namespace Tests\Configurations;

use Doctrine\Persistence\ManagerRegistry;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Configurations\DoctrinePermissionsProvider;
use LaravelDoctrine\ACL\PermissionManager;
use LaravelDoctrine\ACL\Permissions\Driver\PermissionDriver;
use LaravelDoctrine\ACL\Permissions\Permission;
use LaravelDoctrine\ORM\Exceptions\DriverNotFound;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

class PermissionManagerTest extends TestCase
{
    protected PermissionDriver|m\Mock $driver;

    protected PermissionManager|m\Mock $manager;

    protected Container|m\Mock $container;

    protected function setUp(): void
    {
        $this->driver = m::mock(PermissionDriver::class);

        $this->container = m::mock(Container::class);

        $this->manager = new PermissionManager($this->container);
        $this->manager->extend('config', function () {
            return $this->driver;
        });
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testCanDotNotatedArrayOfPermissions(): void
    {
        $this->driver->shouldReceive('getAllPermissions')->once()->andReturn(new Collection([
            'permissionKey2' => [
                'permissionValue1',
                'permissionValue2',
            ],
            'permissionKey3' => [
                'permissionKey4' => [
                    'permissionValue3',
                    'permissionValue4',
                ],
            ],
        ]));

        $config = m::mock(Repository::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');

        $this->assertEquals([
            'permissionKey2.permissionValue1',
            'permissionKey2.permissionValue2',
            'permissionKey3.permissionKey4.permissionValue3',
            'permissionKey3.permissionKey4.permissionValue4',
        ], $this->manager->getPermissionsWithDotNotation());
    }

    public function testWhenShouldUseDefaultPermissionEntity(): void
    {
        $config = m::mock(Repository::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('doctrine');

        // Tests for leading slashes in case someone is providing a manually written FQN
        $config->shouldReceive('get')->with('acl.permissions.entity', null)->andReturn('\\' . Permission::class);

        $this->assertTrue($this->manager->useDefaultPermissionEntity());
    }

    public function testWhenShouldNotUseDefaultPermissionEntityBecauseDriverIsNotDoctrine(): void
    {
        $config = m::mock(Repository::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');
        $config->shouldReceive('get')->with('acl.permissions.entity', null)->andReturn(Permission::class);

        $this->assertFalse($this->manager->useDefaultPermissionEntity());
    }

    public function testWhenShouldNotUseDefaultPermissionEntityBecauseEntityIsDifferent(): void
    {
        $config = m::mock(Repository::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');
        $config->shouldReceive('get')->with('acl.permissions.entity', null)->andReturn('Namespace\Class');

        $this->assertFalse($this->manager->useDefaultPermissionEntity());
    }

    public function testNeedsDoctrine(): void
    {
        $config = m::mock(Repository::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('doctrine');

        $this->assertTrue($this->manager->needsDoctrine());
    }

    public function testDoesNotNeedDoctrine(): void
    {
        $config = m::mock(Repository::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');

        $this->assertFalse($this->manager->needsDoctrine());
    }

    public function testThrowsDriverNotFoundException(): void
    {
        $this->expectException(DriverNotFound::class);
        $manager = new PermissionManager($this->container);
        // Do not extend with any driver, so the requested driver does not exist
        $manager->driver('nonexistent');
    }

    public function testDoctrinePermissionsProviderThrowsExceptionWhenNoEntityClassProvided(): void
    {
        $registry = m::mock(ManagerRegistry::class);
        $config   = m::mock(Repository::class);
        $config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(null);

        $provider = new DoctrinePermissionsProvider($registry, $config);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to configure doctrine permissions. No entity class provided.');
        // Call protected method via reflection
        $reflection = new ReflectionClass($provider);
        $method     = $reflection->getMethod('getPermissionClass');
        $method->setAccessible(true);
        $method->invoke($provider);
    }

    public function testDoctrinePermissionsProviderThrowsExceptionWhenNoEntityManagerFound(): void
    {
        $registry = m::mock(ManagerRegistry::class);
        $config   = m::mock(Repository::class);
        $config->shouldReceive('get')->with('acl.permissions.entity')->andReturn('Some\\Entity\\Class');
        $registry->shouldReceive('getManagerForClass')->with('Some\\Entity\\Class')->andReturn(null);

        $provider = new DoctrinePermissionsProvider($registry, $config);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to configure doctrine permissions. No entity manager found for entity: Some\\Entity\\Class.');
        // Call protected method via reflection
        $reflection = new ReflectionClass($provider);
        $method     = $reflection->getMethod('getEntityManager');
        $method->setAccessible(true);
        $method->invoke($provider);
    }
}
