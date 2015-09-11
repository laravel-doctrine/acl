<?php

namespace LaravelDoctrine\ACL\Contracts;

interface Role
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
