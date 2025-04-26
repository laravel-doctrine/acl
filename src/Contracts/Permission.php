<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Contracts;

interface Permission
{
    public function getName(): string;
}
