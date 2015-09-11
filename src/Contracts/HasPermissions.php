<?php

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\ArrayCollection;

interface HasPermissions
{
    /**
     * @param $permission
     *
     * @return bool
     */
    public function hasPermissionTo($permission);

    /**
     * @return ArrayCollection|Permission[]
     */
    public function getPermissions();
}
