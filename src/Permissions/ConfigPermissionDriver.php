<?php

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;

class ConfigPermissionDriver implements PermissionDriver
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Collection
     */
    public function getAllPermissions()
    {
        return new Collection(
            $this->repository->get('acl.permissions.list', [])
        );
    }
}
