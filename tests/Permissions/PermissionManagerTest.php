<?php

declare(strict_types=1);

namespace Tests\Permissions;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\Permission;
use LaravelDoctrine\ACL\Permissions\PermissionDriver;
use LaravelDoctrine\ACL\Permissions\PermissionManager;
use LaravelDoctrine\ORM\Exceptions\DriverNotFound;
use Mockery as m;
use PHPUnit\Framework\TestCase;

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
}
