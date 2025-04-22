<?php

declare(strict_types=1);

namespace Tests\Permissions;

use Doctrine\DBAL\Driver\Mysqli\Exception\ConnectionFailed;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\DoctrinePermissionDriver;
use LaravelDoctrine\ACL\Permissions\Permission;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class DoctrinePermissionDriverTest extends TestCase
{
    protected Repository|m\Mock $config;

    protected DoctrinePermissionDriver|m\Mock $driver;

    protected ManagerRegistry|m\Mock $registry;

    protected EntityManagerInterface|m\Mock $em;

    protected UnitOfWork|m\Mock $unitOfWork;

    protected EntityPersister|m\Mock $entityPersister;

    protected function setUp(): void
    {
        $this->config          = m::mock(Repository::class);
        $this->registry        = m::mock(ManagerRegistry::class);
        $this->em              = m::mock(EntityManagerInterface::class);
        $this->unitOfWork      = m::mock(UnitOfWork::class);
        $this->entityPersister = m::mock(EntityPersister::class);
        $this->driver          = new DoctrinePermissionDriver($this->registry, $this->config);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testNoEntityManagerFound(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->andReturn(null);

        $collection = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertTrue($collection->isEmpty());
    }

    public function testCanGetAllPermissions(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->andReturn($this->em);

        $this->em->shouldReceive('getUnitOfWork')->andReturn($this->unitOfWork);
        $this->unitOfWork->shouldReceive('getEntityPersister')->with(Permission::class)->andReturn($this->entityPersister);
        $this->entityPersister->shouldReceive('loadAll')->andReturn([
            new Permission('mocked'),
        ]);

        $meta        = new ClassMetadata(Permission::class);
        $meta->table = ['name' => 'permissions'];
        $this->em->shouldReceive('getClassMetadata')->andReturn($meta);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->contains('mocked'));
    }

    public function testShouldNotFailWhenTableDoesNotExist(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->andReturn($this->em);

        $this->em->shouldReceive('getUnitOfWork')->andReturn($this->unitOfWork);
        $this->unitOfWork->shouldReceive('getEntityPersister')->with(Permission::class)->andReturn($this->entityPersister);

        // DBAL 3 removed MysqliException
        $this->entityPersister->shouldReceive('loadAll')->andThrow(new TableNotFoundException(
            new ConnectionFailed('Table not found'),
            null,
        ));

        $meta        = new ClassMetadata(Permission::class);
        $meta->table = ['name' => 'permissions'];
        $this->em->shouldReceive('getClassMetadata')->andReturn($meta);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->isEmpty());
    }
}
