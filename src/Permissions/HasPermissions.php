<?php

namespace LaravelDoctrine\ACL\Permissions;

use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesHasRoles;
use LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;

trait HasPermissions
{
    /**
     * @param  PermissionContract|string|array $name
     * @param  bool                            $requireAll
     * @return bool
     */
    public function hasPermissionTo($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $n) {
                $hasPermission = $this->hasPermissionTo($n);

                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        } else {
            if ($this instanceof HasPermissionsContract) {
                foreach ($this->getPermissions() as $permission) {
                    if ($this->getPermissionName($permission) === $this->getPermissionName($name)) {
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
