<?php

namespace LaravelDoctrine\ACL;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ACL\Contracts\HasPermissions;
use LaravelDoctrine\ACL\Permissions\PermissionManager;
use LaravelDoctrine\ORM\DoctrineManager;
use LaravelDoctrine\ACL\Mappings\RegisterMappedEventSubscribers;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();

        $this->registerPaths();
        $this->registerGatePermissions();
        $this->registerDoctrineMappings();
    }

    protected function registerDoctrineMappings()
    {
        $manager = $this->app->make(DoctrineManager::class);
        $manager->extendAll(RegisterMappedEventSubscribers::class);
    }

    protected function registerPaths()
    {
        $manager = $this->app->make(DoctrineManager::class);
        $permissionManager = $this->app->make(PermissionManager::class);

        if ($permissionManager->useDefaultPermissionEntity()) {
            $manager->addPaths([
                __DIR__ . DIRECTORY_SEPARATOR . 'Permissions',
            ]);
        }
    }

    protected function registerGatePermissions()
    {
        $this->app->afterResolving(Gate::class, function (Gate $gate) {
            $manager = $this->app->make(PermissionManager::class);

            foreach ($manager->getPermissionsWithDotNotation() as $permission) {
                $gate->define($permission, function (HasPermissions $user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            }
        });
    }

    /**
     * Merge config.
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            $this->getConfigPath(), 'acl'
        );
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return __DIR__ . '/../config/acl.php';
    }
}
