=====
Roles
=====

Role Entity
===========

To add Roles to your application, you’ll have to create a ``Role``
entity. This entity should implement
``LaravelDoctrine\ACL\Contracts\Role``. Next you should change the class
name the ``acl.roles.entity`` config to your class, by default this is
set to ``App\Entities\Role``.

.. code:: php

   <?php

   namespace App\Entities;

   use Doctrine\ORM\Mapping as ORM;
   use LaravelDoctrine\ACL\Contracts\Role as RoleContract;

   #[ORM\Entity]
   class Role implements RoleContract
   {
       #[ORM\Id]
       #[ORM\Column(type: "integer")]
       #[ORM\GeneratedValue(strategy: "AUTO")]
       protected $id;

       #[ORM\Column(type: "string")]
       protected $name;

       public function getName()
       {
           return $this->name;
       }
   }



A User has Roles
----------------

Inside your ``User`` entity, you have to define the relation with the
role. The ``User`` entity should implement the
``LaravelDoctrine\ACL\Contracts\HasRoles`` interface. You can use the
``#[ACL\HasRoles]`` attribute to define the relations (instead of
defining the ManyToMany manually). Import
``use LaravelDoctrine\ACL\Attribute as ACL;`` in top of the class.

.. code:: php

   <?php

   use Doctrine\ORM\Mapping as ORM;
   use LaravelDoctrine\ACL\Roles\HasRoles;
   use LaravelDoctrine\ACL\Attribute as ACL;
   use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;

   #[ORM\Entity]
   class User implements HasRolesContract
   {
       use HasRoles;

       #[ORM\Column(type: "integer")]
       #[ORM\Id]
       #[ORM\GeneratedValue(strategy: "AUTO")]
       protected $id;

       #[ACL\HasRoles]
       protected $roles;

       public function getRoles()
       {
           return $this->roles;
       }
   }

How Permissions Are Checked with Roles
--------------------------------------

When you assign roles to a user, permission checks are performed on both
the user and their roles. This means:

-  If a user does **not** have a permission directly, but one of their
   roles has that permission, the user is considered to have that
   permission.
-  If you call ``$user->hasPermissionTo('edit.posts')``, the system
   will:

   1. Check the user’s direct permissions.
   2. If not found, check all permissions assigned to each of the user’s
      roles.

-  This logic is implemented in the ``WithPermissions`` trait (see
   source), which first checks the user’s permissions, then iterates
   over all roles (if any) and checks their permissions recursively.

**Pseudocode:**

.. code:: php

   function hasPermissionTo($permission) {
       if (this->hasPermissionDirectly($permission)) {
           return true;
       }
       foreach ($this->roles as $role) {
           if ($role->hasPermission($permission)) {
               return true;
           }
       }
       return false;
   }

This allows for flexible RBAC: grant permissions to roles, assign roles
to users, and users inherit all permissions from their roles
automatically.

Checking if a User has a certain Role
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The ``LaravelDoctrine\ACL\Roles\HasRoles`` trait provides methods to
check if the User has a certain Role.

.. code:: php

   $user->hasRole($role);
   $user->hasRoleByName('Super Admin');

An array of roles or role names can also checked for.

.. code:: php

   $user->hasRole([$role1,$role2,$role3]);
   $user->hasRoleByName(['User','Admin','Manager']);

Specifying ``true`` for the second argument will check that **all**
roles are present.

.. code:: php

   $user->hasRole([$role1,$role2,$role3], true); //User must have all roles
   $user->hasRoleByName(['User','Admin','Manager'], true);



.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
