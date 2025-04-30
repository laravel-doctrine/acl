=============
Core Concepts
=============


Permissions
===========

A permission is a singular ability to perform an action.

Read more at `permissions <permissions.html>`_.

* Both users and roles can have permissions.
* Implement ``LaravelDoctrine\ACL\Contracts\HasPermissions`` and use the ``WithPermissions`` trait.
* Permissions can be managed via config or Doctrine database tables (see below).


Permission Storage Drivers
==========================

* Config Driver: Store permissions in ``acl.permissions.list`` as simple array in your config file.
* Doctrine Driver: Store permissions in the database. Use the default ``Permission`` entity or your own (must implement `LaravelDoctrine\ACL\Contracts\Permission`).


Roles
=====

A role is a collection of permissions.

Read more about `roles <roles.html>`_.

* Implement ``LaravelDoctrine\ACL\Contracts\Role`` in your Role entity.
* Configure ``acl.roles.entity`` in your config to point to your Role entity.
* Users can have multiple roles; roles can have permissions.


Organisations
=============

An organisation is a group of users.

Read more about `organisations <organisations.html>`_.

* Implement ``LaravelDoctrine\ACL\Contracts\Organisation`` in your organisation entity (e.g., ``Team``).
* Set ``acl.organisations.entity`` in your config.
* Users can belong to one or multiple organisations (implement ``BelongsToOrganisation`` or ``BelongsToOrganisations``).

.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
