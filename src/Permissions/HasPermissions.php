<?php

namespace LaravelDoctrine\ACL\Permissions;

use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles;
use LaravelDoctrine\ACL\Contracts\Permission;

trait HasPermissions
{
    /**
     * @param Permission|string $name
     *
     * @return bool
     */
    public function hasPermissionTo($name)
    {
        if ($this instanceof HasRoles) {
            foreach ($this->getRoles() as $role) {
                if ($role->hasPermissionTo($name)) {
                    return true;
                }
            }
        }

        if ($this instanceof HasPermissionsContract) {
            foreach ($this->getPermissions() as $permission) {
                if ($this->getPermissionName($permission) === $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param Permission|string $permission
     *
     * @return string
     */
    protected function getPermissionName($permission)
    {
        return $permission instanceof Permission ? $permission->getName() : $permission;
    }
}
