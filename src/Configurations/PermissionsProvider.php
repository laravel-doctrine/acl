<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Configurations;

use LaravelDoctrine\ACL\Permissions\Driver\PermissionDriver;
use LaravelDoctrine\ORM\Configuration\Driver;

interface PermissionsProvider extends Driver
{
    /** @param mixed[] $settings */
    public function resolve(array $settings = []): PermissionDriver;
}
