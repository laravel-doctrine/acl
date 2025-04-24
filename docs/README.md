# Laravel Doctrine ACL – Full Documentation

## Introduction

**Laravel Doctrine ACL** brings robust, flexible Access Control List (ACL) support to Laravel and Lumen using Doctrine ORM. It enables you to manage permissions, roles, and organizations in a way that integrates seamlessly with Laravel’s native authorization system.

- Users can belong to organizations.
- Users and roles can have permissions.
- Flexible permission storage (config or database).
- Deep integration with Laravel and Lumen.

---

## Role-Based Access Control (RBAC)

**RBAC is the core and most important feature of this package.**

- Define roles (e.g., Admin, Editor, User) as Doctrine entities.
- Assign roles to users. Users can have multiple roles.
- Assign permissions to roles and/or directly to users.
- Permission checks automatically include both direct user permissions and those inherited through roles.
- Integrates with Laravel's native authorization system (policies, gates, middleware).

This enables you to implement classic RBAC, where permissions are grouped into roles and roles are assigned to users, as well as more advanced scenarios such as direct user permissions and organizational structures.

---

## Installation

### Laravel

1. Install via Composer:
   ```bash
   composer require laravel-doctrine/acl
   ```
2. Register the service provider in `config/app.php`:
   ```php
   LaravelDoctrine\ACL\AclServiceProvider::class,
   ```
3. Publish the configuration:
   ```bash
   php artisan vendor:publish --tag="config" --provider="LaravelDoctrine\ACL\AclServiceProvider"
   ```

### Lumen

1. Install via Composer:
   ```bash
   composer require laravel-doctrine/acl
   ```
2. Register the service provider in `bootstrap/app.php` after the Doctrine ORM provider:
   ```php
   $app->register(LaravelDoctrine\ACL\AclServiceProvider::class);
   ```
3. If you want to override the default config, copy the package config to `config/acl.php`.

---

## Core Concepts

### Roles

- Implement `LaravelDoctrine\ACL\Contracts\Role` in your Role entity.
- Configure `acl.roles.entity` in your config to point to your Role entity.
- Users can have multiple roles; roles can have permissions.

### Permissions

- Both users and roles can have permissions.
- Implement `LaravelDoctrine\ACL\Contracts\HasPermissions` and use the `HasPermissions` trait.
- Permissions can be managed via config or Doctrine database tables (see below).

#### Permission Storage Drivers

- **Config Driver:** Store permissions in `acl.permissions.list` in your config file.
- **Doctrine Driver:** Store permissions in the database. Use the default `Permission` entity or your own (must implement `LaravelDoctrine\ACL\Contracts\Permission`).

### Organisations

- Implement `LaravelDoctrine\ACL\Contracts\Organisation` in your organisation entity (e.g., `Team`).
- Set `acl.organisations.entity` in your config.
- Users can belong to one or multiple organisations (implement `BelongsToOrganisation` or `BelongsToOrganisations`).

---

## Usage

### Assigning Roles and Permissions

- Assign roles to users by implementing `HasRoles` and using the `HasRoles` trait.
- Assign permissions directly to users or roles.

### Checking Permissions

- Use the `hasPermissionTo` method on users or roles:
  ```php
  $user->hasPermissionTo('edit.posts');
  ```
- Integrates with Laravel's `Gate` for policy-based checks.

### Getting All Permissions

- Use the `PermissionManager` to retrieve all permissions:
  ```php
  $manager->getAllPermissions();
  ```

---

## Integration Options

### Laravel

- Full integration with Laravel’s authorization system.
- Use policies, gates, and middleware as with native Laravel.

### Lumen

- Register the service provider in `bootstrap/app.php`.
- Create a config file if you wish to override defaults.

### Doctrine ORM

- All entities (User, Role, Organisation, Permission) are Doctrine entities.
- Use Doctrine annotations or YAML/XML mapping as needed.

---

## Example Entities

### Role Entity

```php
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;

/**
 * @ORM\Entity()
 */
class Role implements RoleContract
{
    // id, name, and getName() as required
}
```

### Organisation Entity

```php
use LaravelDoctrine\ACL\Contracts\Organisation;

/**
 * @ORM\Entity()
 */
class Team implements Organisation
{
    // id, name, and getName() as required
}
```

### User Entity

```php
use LaravelDoctrine\ACL\Contracts\HasRoles;
use LaravelDoctrine\ACL\Contracts\HasPermissions;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Permissions\HasPermissions;

/**
 * @ORM\Entity()
 */
class User implements HasRoles, HasPermissions
{
    use HasRoles, HasPermissions;
    // roles, permissions, and required methods
}
```

---

## Advanced Configuration

- Override default entities in the config (`acl.roles.entity`, `acl.permissions.entity`, etc.).
- Choose permission storage driver (`acl.permissions.driver`: `config` or `doctrine`).
- Use custom permission logic by implementing the relevant contracts.

---

## Resources

- [Introduction](./introduction.md)
- [Installation](./installation.md)
- [Roles](./roles.md)
- [Permissions](./permissions.md)
- [Organisations](./organisations.md)
- [Lumen Integration](./lumen.md)

---
