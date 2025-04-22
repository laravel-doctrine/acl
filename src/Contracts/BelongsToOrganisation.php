<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Contracts;

interface BelongsToOrganisation
{
    public function getOrganisation(): Organisation|null;
}
