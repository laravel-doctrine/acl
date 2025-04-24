<?php

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\ConfigPermissionDriver;
use Mockery as m;
use Tests\TestCase;

class ConfigPermissionDriverTest extends TestCase
{
    /**
     * @var Repository|Mockery\Mock
     */
    protected $config;

    protected ?ConfigPermissionDriver $driver;

    public function setUp(): void
    {
        parent::setUp();
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
