<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions\Driver;

use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Contracts\Permission;

use function array_map;

class Doctrine implements PermissionDriver
{
    public function __construct(protected EntityRepository $repository)
    {
    }

    public function getAllPermissions(): Collection
    {
        // TODO: We can improve performance by fetching only permission names.
        $permissions = $this->repository->findAll();
        $permissions = array_map(static fn (Permission $permission) => $permission->getName(), $permissions);

        return new Collection($permissions);
    }
}
