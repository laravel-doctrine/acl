<?php

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Mysqli\Driver;
use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\ConfigPermissionDriver;
use LaravelDoctrine\ACL\Permissions\DoctrinePermissionDriver;
use LaravelDoctrine\ACL\Permissions\Permission;
use Mockery as m;

class DoctrinePermissionDriverTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Repository|m\Mock
     */
    protected $config;

    /**
     * @var DoctrinePermissionDriver|m\Mock
     */
    protected $driver;

    /**
     * @var ManagerRegistry|m\Mock
     */
    protected $registry;

    /**
     * @var EntityManagerInterface|m\Mock
     */
    protected $em;

    /**
     * @var UnitOfWork|m\Mock
     */
    protected $unitOfWork;

    /**
     * @var EntityPersister|m\Mock
     */
    protected $entityPersister;

    protected function setUp(): void
    {
        $this->config         = m::mock(Repository::class);
        $this->registry       = m::mock(ManagerRegistry::class);
        $this->em             = m::mock(EntityManagerInterface::class);
        $this->unitOfWork     = m::mock(UnitOfWork::class);
        $this->entityPersister = m::mock(EntityPersister::class);
        $this->driver         = new DoctrinePermissionDriver($this->registry, $this->config);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function test_no_entity_manager_found(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->andReturn(null);

        $collection = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertTrue($collection->isEmpty());
    }

    public function test_can_get_all_permissions(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->andReturn($this->em);

        $this->em->shouldReceive('getUnitOfWork')->andReturn($this->unitOfWork);
        $this->unitOfWork->shouldReceive('getEntityPersister')->with(Permission::class)->andReturn($this->entityPersister);
        $this->entityPersister->shouldReceive('loadAll')->andReturn([
            new Permission('mocked'),
        ]);

        $meta        = new ClassMetadata(Permission::class);
        $meta->table = [
            'name' => 'permissions',
        ];
        $this->em->shouldReceive('getClassMetadata')->andReturn($meta);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->contains('mocked'));
    }

    public function test_should_not_fail_when_table_does_not_exist(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->andReturn($this->em);

        $this->em->shouldReceive('getUnitOfWork')->andReturn($this->unitOfWork);
        $this->unitOfWork->shouldReceive('getEntityPersister')->with(Permission::class)->andReturn($this->entityPersister);

        // DBAL 3 removed MysqliException
        $this->entityPersister->shouldReceive('loadAll')->andThrow(new \Doctrine\DBAL\Exception\TableNotFoundException(
            new \Doctrine\DBAL\Driver\Mysqli\Exception\ConnectionFailed('Table not found'), null)
        );

        $meta        = new ClassMetadata(Permission::class);
        $meta->table = [
            'name' => 'permissions',
        ];
        $this->em->shouldReceive('getClassMetadata')->andReturn($meta);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->isEmpty());
    }
}