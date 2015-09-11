<?php

namespace LaravelDoctrine\ACL\Permissions;

use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesHasRoles;
use LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;

trait HasPermissions
{
    /**
     * @param PermissionContract|string $name
     *
     * @return bool
     */
    public function hasPermissionTo($name)
    {
        if ($this instanceof HasPermissionsContract) {
            foreach ($this->getPermissions() as $permission) {
                if ($this->getPermissionName($permission) === $name) {
                    return true;
                }
            }
        }

        if ($this instanceof HasRolesHasRoles) {
            foreach ($this->getRoles() as $role) {
                if ($role instanceof HasPermissionsContract) {
                    if ($role->hasPermissionTo($name)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param PermissionContract|string $permission
     *
     * @return string
     */
    protected function getPermissionName($permission)
    {
        return $permission instanceof PermissionContract ? $permission->getName() : $permission;
    }
}
