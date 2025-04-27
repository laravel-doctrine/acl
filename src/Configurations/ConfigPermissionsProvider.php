<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Configurations;

use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Permissions\Driver\Config;
use LaravelDoctrine\ACL\Permissions\Driver\PermissionDriver;

class ConfigPermissionsProvider implements PermissionsProvider
{
    public function __construct(protected Repository $config)
    {
    }

    /** @param mixed[] $settings */
    public function resolve(array $settings = []): PermissionDriver
    {
        return new Config($this->config->get('acl.permissions.list', []));
    }
}
