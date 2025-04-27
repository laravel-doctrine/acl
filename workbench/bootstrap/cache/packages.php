<?php

declare(strict_types=1);

return [
    'laravel-doctrine/migrations' =>
    [
        'providers' =>
      [0 => 'LaravelDoctrine\\Migrations\\MigrationsServiceProvider'],
    ],
    'laravel-doctrine/orm' =>
    [
        'aliases' =>
        [
            'Doctrine' => 'LaravelDoctrine\\ORM\\Facades\\Doctrine',
            'Registry' => 'LaravelDoctrine\\ORM\\Facades\\Registry',
            'EntityManager' => 'LaravelDoctrine\\ORM\\Facades\\EntityManager',
        ],
        'providers' =>
        [0 => 'LaravelDoctrine\\ORM\\DoctrineServiceProvider'],
    ],
    'laravel/pail' =>
    [
        'providers' =>
      [0 => 'Laravel\\Pail\\PailServiceProvider'],
    ],
    'laravel/tinker' =>
    [
        'providers' =>
      [0 => 'Laravel\\Tinker\\TinkerServiceProvider'],
    ],
    'nesbot/carbon' =>
    [
        'providers' =>
      [0 => 'Carbon\\Laravel\\ServiceProvider'],
    ],
    'nunomaduro/collision' =>
    [
        'providers' =>
      [0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider'],
    ],
    'nunomaduro/termwind' =>
    [
        'providers' =>
      [0 => 'Termwind\\Laravel\\TermwindServiceProvider'],
    ],
    'orchestra/canvas' =>
    [
        'providers' =>
        [0 => 'Orchestra\\Canvas\\LaravelServiceProvider'],
    ],
    'orchestra/canvas-core' =>
    [
        'providers' =>
        [0 => 'Orchestra\\Canvas\\Core\\LaravelServiceProvider'],
    ],
    'sowl/laravel-doctrine-acl' =>
    [
        'providers' =>
        [0 => 'LaravelDoctrine\\ACL\\AclServiceProvider'],
    ],
];
