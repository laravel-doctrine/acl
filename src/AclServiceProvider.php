<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ACL\Contracts\HasPermissions;
use LaravelDoctrine\ACL\Mappings\RegisterMappedEventSubscribers;
use LaravelDoctrine\ORM\DoctrineManager;

use const DIRECTORY_SEPARATOR;

class AclServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfig();

        $this->registerPaths();
        $this->registerGatePermissions();
        $this->registerDoctrineMappings();
    }

    protected function registerDoctrineMappings(): void
    {
        $manager = $this->app->make(DoctrineManager::class);
        $manager->extendAll(RegisterMappedEventSubscribers::class);
    }

    protected function registerPaths(): void
    {
        $permissionManager = $this->app->make(PermissionManager::class);

        if (! $permissionManager->useDefaultPermissionEntity()) {
            return;
        }

        $manager = $this->app->make(DoctrineManager::class);
        $manager->addPaths([
            __DIR__ . DIRECTORY_SEPARATOR . 'Permissions',
        ]);
    }

    protected function registerGatePermissions(): void
    {
        $this->app->afterResolving(Gate::class, function (Gate $gate): void {
            $manager = $this->app->make(PermissionManager::class);

            foreach ($manager->getPermissionsWithDotNotation() as $permission) {
                $gate->define($permission, static function (HasPermissions $user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            }
        });
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(
            $this->getConfigPath(),
            'acl',
        );
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/acl.php';
    }
}
