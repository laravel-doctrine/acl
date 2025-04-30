<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Illuminate\Contracts\Config\Repository as Config;
use LaravelDoctrine\ACL\Mappings\Subscribers\BelongsToOrganisationsSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\BelongsToOrganisationSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\HasPermissionsSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\HasRolesSubscriber;
use LaravelDoctrine\ACL\Mappings\Subscribers\MappedEventSubscriber;
use LaravelDoctrine\ORM\DoctrineExtender;

use function app;

class RegisterMappedEventSubscribers implements DoctrineExtender
{
    /** @var array<class-string<MappedEventSubscriber>> $subscribers */
    protected array $subscribers = [
        BelongsToOrganisationsSubscriber::class,
        BelongsToOrganisationSubscriber::class,
        HasRolesSubscriber::class,
        HasPermissionsSubscriber::class,
    ];

    public function extend(Configuration $configuration, Connection $connection, EventManager $eventManager): void
    {
        $config = app(Config::class);
        foreach ($this->subscribers as $subscriber) {
            $eventManager->addEventSubscriber(new $subscriber($config));
        }
    }
}
