<?php

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Support\Collection;

class DoctrinePermissionDriver implements PermissionDriver
{
    /**
     * @return Collection
     */
    public function getAllPermissions()
    {
        return new Collection([

        ]);
    }
}
