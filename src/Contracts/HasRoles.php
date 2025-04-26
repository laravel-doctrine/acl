<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Contracts;

use Doctrine\Common\Collections\Collection;

interface HasRoles
{
    /** @return Collection|Role[] */
    public function getRoles(): Collection|array;
}
