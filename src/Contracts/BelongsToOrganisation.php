<?php

namespace LaravelDoctrine\ACL\Contracts;

interface BelongsToOrganisation
{
    /**
     * @return Organisation
     */
    public function getOrganisation();
}
