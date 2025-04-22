<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Contracts;

interface Role extends HasPermissions
{
    public function getName(): string;
}
