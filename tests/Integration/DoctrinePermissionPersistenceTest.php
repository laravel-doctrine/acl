<?php

declare(strict_types=1);

namespace Tests\Integration;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use LaravelDoctrine\ACL\Permissions\Permission;
use Tests\TestCase;
use Workbench\App\Entities\Organisation;
use Workbench\App\Entities\Role;
use Workbench\App\Entities\User;
use Workbench\App\Entities\UserSingleOrg;

use function app;
use function assert;
use function entity;

class DoctrinePermissionPersistenceTest extends TestCase
{
    public function testPermissionPersistsAndLoadsFromDb(): void
    {
        $em = app(EntityManagerInterface::class);
        assert($em instanceof EntityManagerInterface);
        $repo = $em->getRepository(User::class);

        // Create user, role, organisation, and permission
        $user         = entity(User::class)->create();
        $role         = new Role('test-role');
        $organisation = new Organisation('test-org');
        $permission   = new Permission('persisted.permission');
        $permission->setName('persisted.permission'); // Just for test coverage
        $role->getPermissions()->add($permission);
        $user->getRoles()->add($role);
        $user->getOrganisations()->add($organisation);
        $user->getPermissions()->add($permission);

        // Persist all entities
        $em->persist($permission);
        $em->persist($role);
        $em->persist($organisation);
        $em->persist($user);
        $em->flush();
        $em->clear();

        $reloaded = $repo->findOneBy(['email' => $user->email]);
        assert($reloaded instanceof User);
        $this->assertNotNull($reloaded);
        $this->assertInstanceOf(User::class, $reloaded);

        // Permissions
        $permissions = $reloaded->getPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->exists(static fn ($key, $perm) => $perm->getName() === 'persisted.permission'));

        $reloadedPermission = $permissions->filter(static fn ($perm) => $perm->getName() === 'persisted.permission')->first();
        assert($reloadedPermission instanceof Permission);
        $this->assertNotNull($reloadedPermission);
        $this->assertInstanceOf(Permission::class, $reloadedPermission);
        $this->assertIsNumeric($reloadedPermission->getId());

        // Roles
        $roles = $reloaded->getRoles();
        $this->assertInstanceOf(Collection::class, $roles);
        $this->assertTrue($roles->exists(static fn ($key, $role) => $role->getName() === 'test-role'));
        $reloadedRole = $roles->filter(static fn ($role) => $role->getName() === 'test-role')->first();
        $this->assertNotNull($reloadedRole);
        $this->assertTrue($reloadedRole->getPermissions()->exists(static fn ($key, $perm) => $perm->getName() === 'persisted.permission'));

        // Organisations
        $organisations = $reloaded->getOrganisations();
        $this->assertInstanceOf(Collection::class, $organisations);
        $this->assertTrue($organisations->exists(static fn ($key, $org) => $org->getName() === 'test-org'));
    }

    public function testUserSingleOrgPermissionsArrayPersistence(): void
    {
        $em = app(EntityManagerInterface::class);
        assert($em instanceof EntityManagerInterface);
        $repo = $em->getRepository(UserSingleOrg::class);

        // Create user and permission
        $user = entity(UserSingleOrg::class)->create();
        $user->setPermissions(['array.permission']);

        // Persist entities
        $em->persist($user);
        $em->flush();
        $em->clear();

        $reloaded = $repo->findOneBy(['email' => $user->email]);
        assert($reloaded instanceof UserSingleOrg);
        $this->assertNotNull($reloaded);
        $this->assertInstanceOf(UserSingleOrg::class, $reloaded);

        // Permissions should be loaded as an array
        $permissions = $reloaded->getPermissions();
        $this->assertIsArray($permissions);
        $this->assertNotEmpty($permissions);
        $this->assertEquals(['array.permission'], $permissions);
    }
}
