<?php

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\PermissionDriver;
use LaravelDoctrine\ACL\Permissions\PermissionManager;
use Mockery as m;

class PermissionManagerTest extends PHPUnit_Framework_TestCase
{
    protected $driver;

    protected $manager;

    protected $container;

    protected function setUp()
    {
        $this->driver = m::mock(PermissionDriver::class);
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

        $this->container = m::mock(Container::class);
        $config          = m::mock(Config::class);

        $this->container->shouldReceive('make')->with('config')->andReturn($config);

        $config->shouldReceive('get')->with('acl.permissions.driver', 'config')->andReturn('config');

        $this->manager = new PermissionManager($this->container);
        $this->manager->extend('config', function () {
            return $this->driver;
        });
    }

    public function test_can_dot_notated_array_of_permissions()
    {
        $this->assertEquals([
            'permission1',
            'permissionKey2.permissionValue1',
            'permissionKey2.permissionValue2',
            'permissionKey3.permissionKey4.permissionValue3',
            'permissionKey3.permissionKey4.permissionValue4'
        ], $this->manager->getPermissionsWithDotNotation());
    }
}
