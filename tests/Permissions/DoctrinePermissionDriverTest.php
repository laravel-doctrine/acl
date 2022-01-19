<?php

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Mysqli\Driver;
use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Permissions\ConfigPermissionDriver;
use LaravelDoctrine\ACL\Permissions\DoctrinePermissionDriver;
use LaravelDoctrine\ACL\Permissions\Permission;
use Mockery as m;

class DoctrinePermissionDriverTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Mockery\Mock
     */
    protected $config;

    /**
     * @var ConfigPermissionDriver
     */
    protected $driver;

    /**
     * @var Mockery\Mock
     */
    protected $registry;

    /**
     * @var Mockery\Mock
     */
    protected $em;

    protected function setUp(): void
    {
        $this->config   = m::mock(Repository::class);
        $this->registry = m::mock(ManagerRegistry::class);
        $this->em       = m::mock(EntityManagerInterface::class);
        $this->driver   = new DoctrinePermissionDriver($this->registry, $this->config);
    }

    public function test_can_get_all_permissions(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->once()->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->once()->andReturn($this->em);

        $this->em->shouldReceive('getUnitOfWork')->once()->andReturn($this->em);
        $this->em->shouldReceive('getEntityPersister')->with(Permission::class)->once()->andReturn($this->em);
        $this->em->shouldReceive('loadAll')->once()->andReturn([
            new Permission('mocked'),
        ]);

        $meta        = new ClassMetadata(Permission::class);
        $meta->table = [
            'name' => 'permissions',
        ];
        $this->em->shouldReceive('getClassMetadata')->once()->andReturn($meta);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->contains('mocked'));
    }

    public function test_should_not_fail_when_table_does_not_exist(): void
    {
        $this->config->shouldReceive('get')->with('acl.permissions.entity')->once()->andReturn(Permission::class);

        $this->registry->shouldReceive('getManagerForClass')->with(Permission::class)->once()->andReturn($this->em);

        $this->em->shouldReceive('getUnitOfWork')->once()->andReturn($this->em);
        $this->em->shouldReceive('getEntityPersister')->with(Permission::class)->once()->andReturn($this->em);



        if (class_exists(MysqliException::class)) {
            $driver = new Driver();
            $exception = new MysqliException('Base table or view not found: 1146 Table \'permissions\' doesn\'t exist', 1146, 1146);
            $tableNotFoundException = DBALException::driverExceptionDuringQuery($driver, $exception, 'SELECT t0.id AS id_1, t0.name AS name_2, t0.modules AS modules_3 FROM permissions t0');

            $this->em->shouldReceive('loadAll')->once()->andThrow($tableNotFoundException);
        } else {
            // DBAL 3 removed MysqliException
            $this->em->shouldReceive('loadAll')->once()->andThrow(new \Doctrine\DBAL\Exception\TableNotFoundException(
                new \Doctrine\DBAL\Driver\Mysqli\Exception\ConnectionFailed('Table not found'), null)
            );
        }

        $meta        = new ClassMetadata(Permission::class);
        $meta->table = [
            'name' => 'permissions',
        ];
        $this->em->shouldReceive('getClassMetadata')->once()->andReturn($meta);

        $permissions = $this->driver->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->isEmpty());
    }
}
