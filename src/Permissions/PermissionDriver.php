<?php

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Support\Collection;

interface PermissionDriver
{
    /**
     * @return Collection
     */
    public function getAllPermissions();
}
