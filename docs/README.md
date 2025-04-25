# Laravel Doctrine ACL – Full Documentation

## Introduction

**Laravel Doctrine ACL** brings robust, flexible Access Control List (ACL) support to Laravel using Doctrine ORM. It enables you to manage permissions, roles, and organizations in a way that integrates seamlessly with Laravel’s native authorization system.

- Users can belong to organizations.
- Users and roles can have permissions.
- Flexible permission storage (config or database).

## Role-Based Access Control (RBAC)

**RBAC is the core and most important feature of this package.**

- Define roles (e.g., Admin, Editor, User) as Doctrine entities.
- Assign roles to users. Users can have multiple roles.
- Assign permissions to roles and/or directly to users.
- Permission checks automatically include both direct user permissions and those inherited through roles.
- Integrates with Laravel's native authorization system (policies, gates, middleware).

This enables you to implement classic RBAC, where permissions are grouped into roles and roles are assigned to users, as well as more advanced scenarios such as direct user permissions and organizational structures.

### Detailed documentation
  - [Roles](Roles.md)
  - [Permissions](Permissions.md)
  - [Organisations](Organisations.md)

## Installation

### Laravel

Install via Composer:
```bash
composer require laravel-doctrine/acl
```

Package should be automatically registered but in case no add it to `bootstrap/providers.php`:
```php
LaravelDoctrine\ACL\AclServiceProvider::class,
```

Publish the configuration:
```bash
php artisan vendor:publish --tag="config" --provider="LaravelDoctrine\ACL\AclServiceProvider"
```
## Usage

### Powerful RBAC with Roles & Permissions

- Assign roles to users by implementing `HasRoles` and using the `HasRoles` trait.
- Assign permissions directly to users or to roles for flexible, scalable RBAC.
- Users inherit all permissions from their assigned roles automatically.

```php
$user->hasRole('admin'); // Check if user has a role
$user->hasPermissionTo('edit.posts'); // Checks both direct and role permissions
$user->hasPermissionTo(['edit.posts', 'publish.articles']); // Any permission
$user->hasPermissionTo(['edit.posts', 'publish.articles'], true); // All permissions
```

### Seamless Integration with Laravel Gate

All permissions are automatically available via Laravel's Gate, allowing you to use familiar authorization patterns:

```php
// In controllers or policies
if (Gate::allows('edit.posts')) {
    // User can edit posts
}
```

### Protecting Routes with RBAC

You can also protect routes using middleware:

```php
// Or via middleware
Route::post('/posts', function () {
    // ...
})->middleware('can:edit.posts');

Route::group(['middleware' => ['can:manage.users']], function () {
    // Only users with 'manage.users' permission (direct or via role) can access these routes
});
```

### Policy-based checks

You can define custom policies for your models or actions and use permissions or roles inside your policy methods:

```php
// app/Policies/PostPolicy.php
public function update(User $user, Post $post)
{
    // Use permissions or roles
    return $user->hasPermissionTo('edit.posts') || $user->hasRole('editor');
}
```

This allows you to combine RBAC with custom business logic for fine-grained authorization.

### Getting All Permissions
Use the `PermissionManager` to retrieve all permissions:

```php
$manager = app(LaravelDoctrine\ACL\Permissions\PermissionManager::class);
$manager->getAllPermissions();
```

## Core Concepts

### Permissions
A permission is a singular ability to perform an action.

Read more about [permissions](permissions.md).

- Both users and roles can have permissions.
- Implement `LaravelDoctrine\ACL\Contracts\HasPermissions` and use the `HasPermissions` trait.
- Permissions can be managed via config or Doctrine database tables (see below).

#### Permission Storage Drivers

- **Config Driver:** Store permissions in `acl.permissions.list` as simple array in your config file.
- **Doctrine Driver:** Store permissions in the database. Use the default `Permission` entity or your own (must implement `LaravelDoctrine\ACL\Contracts\Permission`).

### Roles
A role is a collection of permissions.

Read more about [roles](roles.md).

- Implement `LaravelDoctrine\ACL\Contracts\Role` in your Role entity.
- Configure `acl.roles.entity` in your config to point to your Role entity.
- Users can have multiple roles; roles can have permissions.

### Organisations
An organisation is a group of users.

Read more about [organisations](organisations.md).

- Implement `LaravelDoctrine\ACL\Contracts\Organisation` in your organisation entity (e.g., `Team`).
- Set `acl.organisations.entity` in your config.
- Users can belong to one or multiple organisations (implement `BelongsToOrganisation` or `BelongsToOrganisations`).


## Advanced Configuration

- Override default entities in the config (`acl.roles.entity`, `acl.permissions.entity`, etc.).
- Choose permission storage driver (`acl.permissions.driver`: `config` or `doctrine`).
- Use custom permission logic by implementing the relevant contracts.
