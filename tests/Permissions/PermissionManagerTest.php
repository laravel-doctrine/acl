<?php

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\Permission;
use LaravelDoctrine\ACL\Permissions\PermissionDriver;
use LaravelDoctrine\ACL\Permissions\PermissionManager;
use Mockery as m;

class PermissionManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PermissionDriver
     */
    protected $driver;

    /**
     * @var PermissionManager
     */
    protected $manager;

    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        $this->driver = m::mock(PermissionDriver::class);

        $this->container = m::mock(Container::class);

        $this->manager = new PermissionManager($this->container);
        $this->manager->extend('config', function () {
            return $this->driver;
        });
    }

    public function test_can_dot_notated_array_of_permissions()
    {
        $this->driver->shouldReceive('getAllPermissions')->once()->andReturn(new Collection([
            'permission1',
            'permissionKey2' => [
                'permissionValue1',
                'permissionValue2'
            ],
            'permissionKey3' => [
                'permissionKey4' => [
                    'permissionValue3',
                    'permissionValue4'
                ]
            ]
        ]));

        $config = m::mock(Config::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');

        $this->assertEquals([
            'permission1',
            'permissionKey2.permissionValue1',
            'permissionKey2.permissionValue2',
            'permissionKey3.permissionKey4.permissionValue3',
            'permissionKey3.permissionKey4.permissionValue4'
        ], $this->manager->getPermissionsWithDotNotation());
    }

    public function test_when_should_use_default_permission_entity()
    {
        $config = m::mock(Config::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('doctrine');

        // Tests for leading slashes in case someone is providing a manually written FQN
        $config->shouldReceive('get')->with('acl.permissions.entity', null)->andReturn("\\" . Permission::class);

        $this->assertTrue($this->manager->useDefaultPermissionEntity());
    }

    public function test_when_should_not_use_default_permission_entity_because_driver_is_not_doctrine()
    {
        $config = m::mock(Config::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');
        $config->shouldReceive('get')->with('acl.permissions.entity', null)->andReturn(Permission::class);

        $this->assertFalse($this->manager->useDefaultPermissionEntity());
    }

    public function test_when_should_not_use_default_permission_entity_because_entity_is_different()
    {
        $config = m::mock(Config::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');
        $config->shouldReceive('get')->with('acl.permissions.entity', null)->andReturn('Namespace\Class');

        $this->assertFalse($this->manager->useDefaultPermissionEntity());
    }
}
