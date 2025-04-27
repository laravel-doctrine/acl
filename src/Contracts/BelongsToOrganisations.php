<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\Collection;

interface BelongsToOrganisations
{
    /** @return Collection<int, Organisation>|Organisation[] */
    public function getOrganisations(): Collection|array;
}
