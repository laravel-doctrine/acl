<?php

declare(strict_types=1);

namespace Tests\Permissions;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\ConfigPermissionDriver;
use Mockery as m;
use Mockery\Mock;
use Tests\TestCase;

class ConfigPermissionDriverTest extends TestCase
{
    protected Repository|Mock $config;

    protected ConfigPermissionDriver|null $driver;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = m::mock(Repository::class);
        $this->driver = new ConfigPermissionDriver($this->config);
    }

    public function testCanGetAllPermissions(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.list', [])->once()->andReturn(['mocked']);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->contains('mocked'));
    }
}
