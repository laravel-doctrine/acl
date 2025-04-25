<?php

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\Collection;

interface HasPermissions
{
    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermissionTo($permission);

    /**
     * @return Collection|Permission[]
     */
    public function getPermissions();
}
