<?php

namespace LaravelDoctrine\ACL;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Illuminate\Container\Container;
use LaravelDoctrine\ACL\Mappings\Subscribers\BelongsToOrganisationsSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\BelongsToOrganisationSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\HasPermissionsSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\HasRolesSubscriber;
use LaravelDoctrine\ORM\DoctrineExtender;

class RegisterMappedEventSubscribers implements DoctrineExtender
{
    /**
     * @var array
     */
    protected $subscribers = [
        BelongsToOrganisationsSubscriber::class,
        BelongsToOrganisationSubscriber::class,
        HasRolesSubscriber::class,
        HasPermissionsSubscriber::class,
    ];

    /**
     * @var Container
     */
    protected $container;

    /**
     * RegisterMappedEventSubscribers constructor.
     */
    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /**
     * @param Configuration $configuration
     * @param Connection    $connection
     * @param EventManager  $eventManager
     */
    public function extend(Configuration $configuration, Connection $connection, EventManager $eventManager)
    {
        foreach ($this->subscribers as $subscriber) {
            $eventManager->addEventSubscriber(
                new $subscriber(
                    $configuration->getMetadataDriverImpl()->getReader(),
                    $this->container['config']
                )
            );
        }
    }
}
