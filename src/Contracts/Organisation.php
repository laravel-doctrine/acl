<?php

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\ArrayCollection;

interface Organisation
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return ArrayCollection|BelongsToOrganisations[]
     */
    public function getUsers();
}
