<?php

namespace LaravelDoctrine\ACL\Roles;

use LaravelDoctrine\ACL\Contracts\Role;

trait HasRoles
{
    /**
     * @param  Role|array $role
     * @param  bool       $requireAll
     * @return bool
     */
    public function hasRole($role, $requireAll = false)
    {
        if (is_array($role)) {
            foreach ($role as $r) {
                $hasRole = $this->hasRole($r);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        } else {
            foreach ($this->getRoles() as $ownedRole) {
                if ($ownedRole === $role) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  string|array $name
     * @param  bool         $requireAll
     * @return bool
     */
    public function hasRoleByName($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $n) {
                $hasRole = $this->hasRoleByName($n);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        } else {
            foreach ($this->getRoles() as $ownedRole) {
                if ($ownedRole->getName() === $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return ArrayCollection|Role[]
     */
    abstract public function getRoles();
}
