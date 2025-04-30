<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions\Driver;

use Illuminate\Support\Collection;

class Config implements PermissionDriver
{
    protected Collection $collection;

    /** @var array<string> */
    public function __construct(array $permissions)
    {
        $this->collection = new Collection($permissions);
    }

    public function getAllPermissions(): Collection
    {
        return $this->collection;
    }
}
