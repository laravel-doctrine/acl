<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\Collection;

interface HasPermissions
{
    public function hasPermissionTo(string $permission): bool;

    /** @return Collection<int, Permission>|Permission[]|Collection<string>|string[] */
    public function getPermissions(): Collection|array;
}
