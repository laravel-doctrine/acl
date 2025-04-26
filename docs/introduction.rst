============
Introduction
============

Laravel Doctrine ACL brings robust, flexible Access Control List (ACL) support to
Laravel using Doctrine ORM. It enables you to manage permissions, roles, and organisations
in a way that integrates seamlessly with Laravel’s native authorization system.

* Users and roles can have permissions.
* Users can belong to organisations.
* Flexible permission storage (config or database).


Role-Based Access Control (RBAC)
================================

RBAC is the core and most important feature of this package.

* Define roles (e.g., Admin, Editor, User) as Doctrine entities.
* Assign roles to users. Users can have multiple roles.
* Assign permissions to roles and/or directly to users.
* Permission checks automatically include both direct user permissions and those
  inherited through roles.
* Integrates with Laravel's native authorization system (policies, gates, middleware).

This enables you to implement classic RBAC, where permissions are grouped into roles and
roles are assigned to users, as well as more advanced scenarios such as direct user permissions
and organisational structures.



.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
