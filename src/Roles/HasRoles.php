<?php

namespace LaravelDoctrine\ACL\Roles;

use LaravelDoctrine\ACL\Contracts\Role;

trait HasRoles
{
    /**
     * @param Role $role
     *
     * @return bool
     */
    public function hasRole(Role $role)
    {
        foreach ($this->getRoles() as $ownedRole) {
            if ($ownedRole === $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasRoleByName($name)
    {
        foreach ($this->getRoles() as $ownedRole) {
            if ($ownedRole->getName() === $name) {
                return true;
            }
        }

        return false;
    }
}
