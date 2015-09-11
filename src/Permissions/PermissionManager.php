<?php

namespace LaravelDoctrine\ACL\Permissions;

use LaravelDoctrine\ACL\Manager;

class PermissionManager extends Manager
{
    /**
     * Get the default driver name.
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'config';
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * @return string
     */
    public function getClassSuffix()
    {
        return 'PermissionDriver';
    }
}
