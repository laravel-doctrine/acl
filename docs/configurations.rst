===========
Config File
===========

This document describes the options available in the `config/acl.php` configuration file for the Laravel Doctrine ACL package.

Permissions
===========

.. code-block:: php

    'permissions' => [
        'driver' => 'config',
        'entity' => LaravelDoctrine\ACL\Permissions\Permission::class,
        'list'   => [],
    ],

- **driver**: The permissions driver to use. Supported drivers:

  - `config`: Permissions are defined statically in the `list` array below.
  - `doctrine`: Permissions are managed as Doctrine entities in the database.

- **list**: (Only for `config` driver) An array of permission names to be recognized by the system. Example: `['edit.posts', 'delete.posts']`

  .. code-block:: php

      'list' => [
          'edit.posts',
          'delete.posts',
      ],

- **entity**: (Only for `doctrine` driver) The fully qualified class name of your Permission entity. Defaults to `LaravelDoctrine\ACL\Permissions\Permission`.

Roles
=====

.. code-block:: php

    'roles' => [
        'entity' => App\Entities\Role::class,
    ],

- **entity**: The fully qualified class name of your Role entity. By default, this is `App\Entities\Role`. You may customize this to point to your own Role entity class implementing `LaravelDoctrine\ACL\Contracts\Role`.


Organisations
=============

.. code-block:: php

    'organisations' => [
        'entity' => App\Entities\Organisation::class,
    ],

- **entity**: The fully qualified class name of your Organisation entity. By default, this is `App\Entities\Organisation`. You may customize this to point to your own Organisation entity class implementing `LaravelDoctrine\ACL\Contracts\Organisation`.


Entities
========

You can use the stubs as a starting point for your own entities.

You may publish the stubs for the entities by running the following command:

.. code-block:: bash

    php artisan vendor:publish --tag="acl-entities"

This command will publish the stubs for the entities to the `app/Entities` directory.

  * [`app/Entities/Permission.php`](../stubs/Permission.php) - The stub for the Permission entity.
  * [`app/Entities/Role.php`](../stubs/Role.php) - The stub for the Role entity.
  * [`app/Entities/Organisation.php`](../stubs/Organisation.php) - The stub for the Organisation entity.

> **Note**: Pay attention that we published a stub for Permission so you should update `acl.permission.entity` in the config file.

.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
