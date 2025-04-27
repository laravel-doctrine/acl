<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions\Driver;

use Illuminate\Support\Collection;

interface PermissionDriver
{
    public function getAllPermissions(): Collection;
}
