<?php

declare(strict_types=1);

namespace Tests\Integration;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use LaravelDoctrine\ACL\Permissions\Permission;
use Tests\TestCase;
use Workbench\App\Entities\User;
use Workbench\App\Entities\UserSingleOrg;

class DoctrinePermissionPersistenceTest extends TestCase
{
    public function test_permission_persists_and_loads_from_db(): void
    {
        /** @var EntityManagerInterface $em */
        $em = app(EntityManagerInterface::class);
        $repo = $em->getRepository(User::class);

        // Create user, role, organisation, and permission
        $user = entity(User::class)->create();
        $role = new \Workbench\App\Entities\Role('test-role');
        $organisation = new \Workbench\App\Entities\Organisation('test-org');
        $permission = new Permission('persisted.permission');
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

        /** @var User $reloaded */
        $reloaded = $repo->findOneBy(['email' => $user->email]);
        $this->assertNotNull($reloaded);
        $this->assertInstanceOf(User::class, $reloaded);

        // Permissions
        $permissions = $reloaded->getPermissions();
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue($permissions->exists(fn($key, $perm) => $perm->getName() === 'persisted.permission'));

        /** @var Permission $reloadedPermission */
        $reloadedPermission = $permissions->filter(fn($perm) => $perm->getName() === 'persisted.permission')->first();
        $this->assertNotNull($reloadedPermission);
        $this->assertInstanceOf(Permission::class, $reloadedPermission);
        $this->assertIsNumeric($reloadedPermission->getId());

        // Roles
        $roles = $reloaded->getRoles();
        $this->assertInstanceOf(Collection::class, $roles);
        $this->assertTrue($roles->exists(fn($key, $role) => $role->getName() === 'test-role'));
        $reloadedRole = $roles->filter(fn($role) => $role->getName() === 'test-role')->first();
        $this->assertNotNull($reloadedRole);
        $this->assertTrue($reloadedRole->getPermissions()->exists(fn($key, $perm) => $perm->getName() === 'persisted.permission'));

        // Organisations
        $organisations = $reloaded->getOrganisations();
        $this->assertInstanceOf(Collection::class, $organisations);
        $this->assertTrue($organisations->exists(fn($key, $org) => $org->getName() === 'test-org'));
    }

    public function test_user_single_org_permissions_array_persistence(): void
    {
        /** @var EntityManagerInterface $em */
        $em = app(EntityManagerInterface::class);
        $repo = $em->getRepository(\Workbench\App\Entities\UserSingleOrg::class);

        // Create user and permission
        $user = entity(UserSingleOrg::class)->create();
        $user->setPermissions(['array.permission']);

        // Persist entities
        $em->persist($user);
        $em->flush();
        $em->clear();

        /** @var \Workbench\App\Entities\UserSingleOrg $reloaded */
        $reloaded = $repo->findOneBy(['email' => $user->email]);
        $this->assertNotNull($reloaded);
        $this->assertInstanceOf(\Workbench\App\Entities\UserSingleOrg::class, $reloaded);

        // Permissions should be loaded as an array
        $permissions = $reloaded->getPermissions();
        $this->assertIsArray($permissions);
        $this->assertNotEmpty($permissions);
        $this->assertEquals([
            'array.permission'
        ], $permissions);
    }
}
