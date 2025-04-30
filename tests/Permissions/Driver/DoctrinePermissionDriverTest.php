<?php

declare(strict_types=1);

namespace Tests\Permissions;

use Doctrine\ORM\EntityRepository;
use LaravelDoctrine\ACL\Permissions\Driver\Doctrine;
use LaravelDoctrine\ACL\Permissions\Permission;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class DoctrinePermissionDriverTest extends TestCase
{
    public function testCanGetAllPermissions(): void
    {
        $repository = m::mock(EntityRepository::class);
        $repository->shouldReceive('findAll')->andReturn([
            new Permission('mocked'),
        ]);

        $driver = new Doctrine($repository);

        $permissions = $driver->getAllPermissions();
        $this->assertTrue($permissions->contains('mocked'));
    }
}
