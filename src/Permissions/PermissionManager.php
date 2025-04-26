<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use function is_array;
use function is_numeric;
use function ltrim;

/** @method Collection getAllPermissions() */
class PermissionManager extends Manager
{
    /** @return array<int, string> */
    public function getPermissionsWithDotNotation(): array
    {
        $permissions = $this->driver()->getAllPermissions();

        $list = $this->convertToDotArray(
            $permissions->toArray(),
        );

        return Arr::flatten($list);
    }

    /**
     * @param array<string, mixed>|string $permissions
     *
     * @return array<int, string>
     */
    protected function convertToDotArray(array|string $permissions, string $prepend = ''): array
    {
        $list = [];
        if (is_array($permissions)) {
            foreach ($permissions as $key => $permission) {
                $list[] = $this->convertToDotArray($permission, ! is_numeric($key) ? $prepend . $key . '.' : $prepend);
            }
        } else {
            $list[] = $prepend . $permissions;
        }

        return $list;
    }

    /**
     * Get the default driver name.
     */
    public function getDefaultDriver(): string
    {
        return $this->container->make('config')->get('acl.permissions.driver', 'config');
    }

    public function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    public function getClassSuffix(): string
    {
        return 'PermissionDriver';
    }

    public function useDefaultPermissionEntity(): bool
    {
        if (! $this->needsDoctrine()) {
            return false;
        }

        $entityFqn = $this->container->make('config')->get('acl.permissions.entity', '');
        $entityFqn = ltrim($entityFqn, '\\');

        return $entityFqn === Permission::class;
    }

    public function needsDoctrine(): bool
    {
        return $this->getDefaultDriver() === 'doctrine';
    }
}
