<?php

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Manager;

/**
 * @method Collection getAllPermissions()
 */
class PermissionManager extends Manager
{
    /**
     * @return array
     */
    public function getPermissionsWithDotNotation()
    {
        $permissions = $this->driver()->getAllPermissions();

        $list = $this->convertToDotArray(
            $permissions->toArray()
        );

        return Arr::flatten($list);
    }

    /**
     * @param array|string $permissions
     * @param string       $prepend
     *
     * @return array
     */
    protected function convertToDotArray($permissions, $prepend = '')
    {
        $list = [];
        if (is_array($permissions)) {
            foreach ($permissions as $key => $permission) {
                $list[] = $this->convertToDotArray($permission, (!is_numeric($key)) ? $prepend . $key . '.' : $prepend);
            }
        } else {
            $list[] = $prepend . $permissions;
        }

        return $list;
    }

    /**
     * Get the default driver name.
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->container->make('config')->get('acl.permissions.driver', 'config');
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    /**
     * @return string
     */
    public function getClassSuffix(): string
    {
        return 'PermissionDriver';
    }

    /**
     * @return bool
     */
    public function useDefaultPermissionEntity(): bool
    {
        if (!$this->needsDoctrine()) {
            return false;
        }

        $entityFqn = $this->container->make('config')->get('acl.permissions.entity', '');
        $entityFqn = ltrim($entityFqn, "\\");

        return $entityFqn === Permission::class;
    }

    /**
     * @return bool
     */
    public function needsDoctrine(): bool
    {
        return $this->getDefaultDriver() === 'doctrine';
    }
}