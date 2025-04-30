<?php

declare(strict_types=1);

use LaravelDoctrine\ACL\AclServiceProvider;
use LaravelDoctrine\Migrations\MigrationsServiceProvider;
use LaravelDoctrine\ORM\DoctrineServiceProvider;

return [
    DoctrineServiceProvider::class,
    MigrationsServiceProvider::class,
    AclServiceProvider::class,
];
