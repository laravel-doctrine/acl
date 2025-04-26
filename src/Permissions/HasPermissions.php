<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions;

use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesHasRoles;
use LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;

use function is_array;

trait HasPermissions
{
    public function hasPermissionTo(PermissionContract|string|array $name, bool $requireAll = false): bool
    {
        if (is_array($name)) {
            foreach ($name as $n) {
                $hasPermission = $this->hasPermissionTo($n);

                if ($hasPermission && ! $requireAll) {
                    return true;
                }

                if (! $hasPermission && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        }

        if ($this instanceof HasPermissionsContract) {
            foreach ($this->getPermissions() as $permission) {
                if ($this->getPermissionName($permission) === $this->getPermissionName($name)) {
                    return true;
                }
            }
        }

        if ($this instanceof HasRolesHasRoles) {
            foreach ($this->getRoles() as $role) {
                if (! ($role instanceof HasPermissionsContract)) {
                    continue;
                }

                if ($role->hasPermissionTo($name)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function getPermissionName(PermissionContract|string $permission): string
    {
        return $permission instanceof PermissionContract ? $permission->getName() : $permission;
    }
}
