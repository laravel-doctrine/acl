<?php

namespace LaravelDoctrine\ACL\Contracts;

interface Permission
{
    /**
     * @return string
     */
    public function getName();
}
