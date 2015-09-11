<?php

namespace LaravelDoctrine\ACL;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ACL\Mappings\AnnotationLoader;
use LaravelDoctrine\ACL\Mappings\Subscribers\BelongsToOrganisationsSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\BelongsToOrganisationSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\HasRolesSubscriber;
use LaravelDoctrine\ORM\DoctrineManager;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = true;

    /**
     * @var array
     */
    protected $subscribers = [
        BelongsToOrganisationsSubscriber::class,
        BelongsToOrganisationSubscriber::class,
        HasRolesSubscriber::class
    ];

    /**
     * @param DoctrineManager $manager
     */
    public function boot(DoctrineManager $manager)
    {
        $manager->extendAll(function (Configuration $configuration, Connection $connection, EventManager $evm) {
            foreach ($this->subscribers as $subscriber) {
                $evm->addEventSubscriber(
                    $this->app->make($subscriber, [$configuration->getMetadataDriverImpl()->getReader()])
                );
            }
        });
    }

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        AnnotationRegistry::registerLoader([
            new AnnotationLoader,
            'loadClass'
        ]);
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            'auth',
            'registry',
            Gate::class
        ];
    }
}
