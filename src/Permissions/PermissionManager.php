<?php

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Manager;

/**
 * @method Collection getAllPermissions()
 */
class PermissionManager extends Manager
{
    /**
     * Get the default driver name.
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->container->make('config')->get('acl.permissions.driver', 'config');
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

    /**
     * @return bool
     */
    public function needsDoctrine()
    {
        return $this->getDefaultDriver() === 'doctrine';
    }
}
