<?php

namespace LaravelDoctrine\ACL;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ACL\Contracts\HasPermissions;
use LaravelDoctrine\ACL\Mappings\AnnotationLoader;
use LaravelDoctrine\ACL\Permissions\PermissionManager;
use LaravelDoctrine\ORM\DoctrineManager;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $manager           = $this->app->make(DoctrineManager::class);
        $gate              = $this->app->make(Gate::class);
        $permissionManager = $this->app->make(PermissionManager::class);

        if (!$this->isLumen()) {
            $this->publishes([
                $this->getConfigPath() => config_path('acl.php'),
            ], 'config');
        }

        $manager->extendAll(RegisterMappedEventSubscribers::class);

        if ($permissionManager->needsDoctrine()) {
            $manager->addPaths([
                __DIR__ . DIRECTORY_SEPARATOR . 'Permissions',
            ]);
        }

        $this->definePermissions($gate, $permissionManager);
    }

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->registerAnnotations();
    }

    /**
     * @param Gate              $gate
     * @param PermissionManager $manager
     */
    protected function definePermissions(Gate $gate, PermissionManager $manager)
    {
        foreach ($manager->getPermissionsWithDotNotation() as $permission) {
            $gate->define($permission, function (HasPermissions $user) use ($permission) {
                return $user->hasPermissionTo($permission);
            });
        }
    }

    /**
     * Register annotations.
     */
    protected function registerAnnotations()
    {
        AnnotationRegistry::registerLoader([
            new AnnotationLoader,
            'loadClass',
        ]);
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

    /**
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}
