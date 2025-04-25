#########
# Usage #
#########

Powerful RBAC with Roles & Permissions
======================================

* Assign roles to users by implementing ``HasRoles`` and using the ``HasRoles`` trait.
* Assign permissions directly to users or to roles for flexible, scalable RBAC.
* Users inherit all permissions from their assigned roles automatically.

.. code-block:: php

  $user->hasRole('admin'); // Check if user has a role
  $user->hasPermissionTo('edit.posts'); // Checks both direct and role permissions
  $user->hasPermissionTo(['edit.posts', 'publish.articles']); // Any permission
  $user->hasPermissionTo(['edit.posts', 'publish.articles'], true); // All permissions

Seamless Integration with Laravel Gate
======================================

All permissions are automatically available via Laravel's Gate, allowing you to use
familiar authorization patterns:

.. code-block:: php

   // In controllers or policies
   if (Gate::allows('edit.posts')) {
      // User can edit posts
   }

Protecting Routes with RBAC
===========================

You can also protect routes using middleware:

.. code-block:: php

    // Or via middleware
    Route::post('/posts', function () {
        // ...
    })->middleware('can:edit.posts');

    Route::group(['middleware' => ['can:manage.users']], function () {
        // Only users with 'manage.users' permission (direct or via role) can access these routes
    });

Policy-based checks
===================

You can define custom policies for your models or actions and use permissions or roles
inside your policy methods:

.. code-block:: php

    // app/Policies/PostPolicy.php
    public function update(User $user, Post $post)
    {
        // Use permissions or roles
        return $user->hasPermissionTo('edit.posts') || $user->hasRole('editor');
    }


This allows you to combine RBAC with custom business logic for fine-grained authorization.

Getting All Permissions
=======================

Use the `PermissionManager` to retrieve all permissions:

.. code-block:: php

   $manager = app(LaravelDoctrine\ACL\Permissions\PermissionManager::class);
   $manager->getAllPermissions();



.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
