<?php

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\ArrayCollection;

interface HasPermissions
{
    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermissionTo($permission);

    /**
     * @return ArrayCollection|Permission[]
     */
    public function getPermissions();
}
