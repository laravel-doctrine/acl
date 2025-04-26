<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Support\Collection;

interface PermissionDriver
{
    public function getAllPermissions(): Collection;
}
