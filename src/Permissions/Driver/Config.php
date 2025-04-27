<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions\Driver;

use Illuminate\Support\Collection;

class Config implements PermissionDriver
{
    protected Collection $collection;

    /** @var array<string> */
    public function __construct(protected array $permissions)
    {
        $this->collection = new Collection($this->permissions);
    }

    public function getAllPermissions(): Collection
    {
        return $this->collection;
    }
}
