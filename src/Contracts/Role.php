<?php

namespace LaravelDoctrine\ACL\Contracts;

interface Role extends HasPermissions
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ArrayCollection|HasRoles[]
     */
    public function getUsers();
}
