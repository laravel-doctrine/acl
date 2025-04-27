===========
Permissions
===========

Both User and Role can have permissions. To add this behaviour we can
simply add the ``LaravelDoctrine\ACL\Contracts\HasPermissions``
interface to them. We can also add the
``LaravelDoctrine\ACL\Permissions\HasPermissions`` trait to have some
nice helpers. We can use the ``#[ACL\HasPermissions]`` attribute to
define the permissions relation.

.. code:: php

   <?php

   use Doctrine\ORM\Mapping as ORM;
   use LaravelDoctrine\ACL\Attribute as ACL;
   use LaravelDoctrine\ACL\Permissions\HasPermissions;
   use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionContract;

   #[ORM\Entity]
   class User implements HasPermissionContract
   {
       use HasPermissions;

       #[ACL\HasPermissions]
       protected $permissions;

       public function getPermissions()
       {
           return $this->permissions;
       }
   }

Getting all permissions
~~~~~~~~~~~~~~~~~~~~~~~

You can get a list of all permissions with the
``LaravelDoctrine\ACL\PermissionManager``

.. code:: php

   $manager = app(PermissionManager::class);
   $manager->getAllPermissions();

Config Permissions
~~~~~~~~~~~~~~~~~~

By setting the permissions driver to ``config``, no additional
``permissions`` table will be created, but permissions will be expected
to be added inside the config: ``acl.permissions.list`` The given
permissions will now be stored in the Entity as json.

.. code:: php

   <?php

   return [
       'permissions' => [
           'driver' => 'config',
           'list' => [
               'create.posts'
           ]
       ]
   ];

Database Permissions
~~~~~~~~~~~~~~~~~~~~

By setting the permissions driver to ``doctrine``, an additional
``permissions`` table will be created. Permissions will be stored in
Pivot tables for roles and users. A default ``Permission`` entity is
included in this package. You can replace that one by your own inside
the config as long as it implements the
``LaravelDoctrine\ACL\Contracts\Permission`` interface.

Checking if a User or Role has permission
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

On the User or Role entity
^^^^^^^^^^^^^^^^^^^^^^^^^^

When adding the ``LaravelDoctrine\ACL\Permissions\HasPermissions`` trait
you will get a ``hasPermissionTo`` method. First the ``User`` entity
will check if it has the right permission itself. If not it will search
in its roles. If none of them has permission, it will return false.

.. code:: php

   $user->hasPermissionTo('create.posts');
   $role->hasPermissionTo('create.posts');

An array of permissions can also checked for.

.. code:: php

   $user->hasPermissionTo(['create.posts','create.page']);
   $role->hasPermissionTo(['create.posts','create.page']);

Specifying ``true`` for the second argument will check that **all**
permissions are present.

.. code:: php

   $user->hasPermissionTo(['create.posts','create.page'], true); //all permissions are required to return true
   $role->hasPermissionTo(['create.posts','create.page'], true);

Using the Gate helper
^^^^^^^^^^^^^^^^^^^^^

All permissions are automatically defined inside Laravel’s Gate helper.

.. code:: php

   Gate::allows('create.posts');
   @can('create.posts');
   $user->can('create.posts');

Using Permissions Middleware with Gate
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can use Laravel’s built-in ``can`` middleware to protect routes
based on permissions defined by Gate (and thus by this package):

.. code:: php

   // Require a specific permission for this route
   Route::post('/posts', function () {
       // Only users with the 'create.posts' permission can access this route
   })->middleware('can:create.posts');

   // Or using the route's can() method (Laravel 9+)
   Route::post('/posts', function () {
       // Only users with the 'create.posts' permission can access this route
   })->can('create.posts');

If the user does not have the required permission, Laravel will return a
403 response automatically.

You can also check multiple permissions by creating custom middleware or
using Gate logic in controllers.



.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
