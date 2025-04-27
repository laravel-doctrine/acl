<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\Collection;

interface HasRoles
{
    /** @return Collection<int, Role>|Role[] */
    public function getRoles(): Collection|array;
}
