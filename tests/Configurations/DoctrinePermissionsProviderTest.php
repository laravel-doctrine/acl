<?php

declare(strict_types=1);

namespace Tests\Configurations;

use Doctrine\Persistence\ManagerRegistry;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Configurations\DoctrinePermissionsProvider;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

class DoctrinePermissionsProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testThrowsExceptionWhenNoEntityClassProvided(): void
    {
        $registry = m::mock(ManagerRegistry::class);
        $config   = m::mock(Repository::class);
        $config->shouldReceive('get')->with('acl.permissions.entity')->andReturn(null);

        $provider = new DoctrinePermissionsProvider($registry, $config);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to configure doctrine permissions. No entity class provided.');
        // Call protected method via reflection
        $reflection = new ReflectionClass($provider);
        $method     = $reflection->getMethod('getPermissionClass');
        $method->invoke($provider);
    }

    public function testThrowsExceptionWhenNoEntityManagerFound(): void
    {
        $registry = m::mock(ManagerRegistry::class);
        $config   = m::mock(Repository::class);
        $config->shouldReceive('get')->with('acl.permissions.entity')->andReturn('Some\\Entity\\Class');
        $registry->shouldReceive('getManagerForClass')->with('Some\\Entity\\Class')->andReturn(null);

        $provider = new DoctrinePermissionsProvider($registry, $config);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to configure doctrine permissions. No entity manager found for entity: Some\\Entity\\Class.');
        // Call protected method via reflection
        $reflection = new ReflectionClass($provider);
        $method     = $reflection->getMethod('getEntityManager');
        $method->invoke($provider);
    }
}
