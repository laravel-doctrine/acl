<?php

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\ArrayCollection;

interface BelongsToOrganisations
{
    /**
     * @return ArrayCollection|Organisation[]
     */
    public function getOrganisations();
}
