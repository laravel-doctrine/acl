<?php

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\ConfigPermissionDriver;
use Mockery as m;

class ConfigPermissionDriverTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Mockery\Mock
     */
    protected $config;

    /**
     * @var ConfigPermissionDriver
     */
    protected $driver;

    protected function setUp(): void
    {
        $this->config = m::mock(Repository::class);
        $this->driver = new ConfigPermissionDriver($this->config);
    }

    public function test_can_get_all_permissions(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.list', [])->once()->andReturn(['mocked']);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->contains('mocked'));
    }
}
