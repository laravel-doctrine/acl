<?php

declare(strict_types=1);

use LaravelDoctrine\ACL\Permissions\Permission;
use Workbench\App\Entities\Organisation;
use Workbench\App\Entities\Role;

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Available drivers: config|doctrine
    | When set to config, add the permission names to list
    |
    */
    'permissions'   => [
        'driver' => 'doctrine',
        'entity' => Permission::class,
        'list'   => [
            'role.attach',
            'role.detach',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    'roles'         => [
        'entity' => Role::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Organisations
    |--------------------------------------------------------------------------
    */
    'organisations' => [
        'entity' => Organisation::class,
    ],
];
