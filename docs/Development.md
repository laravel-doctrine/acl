# Laravel Doctrine ACL Development Guide

This document explains the structure and components of the Laravel Doctrine ACL library, detailing how the code is organized and how the main components interact.

## Core Structure

Laravel Doctrine ACL provides role-based and permission-based access control integration with Laravel using Doctrine ORM. The library consists of several core components:

1. **Service Provider**: The entry point for Laravel integration
2. **Managers**: For handling permissions, roles, and organizations
3. **Contracts**: Interfaces defining the core functionality
4. **Traits**: Implementation of the interface contracts
5. **Mappings**: Doctrine mappings for ACL relationships using PHP 8 attributes
6. **Subscribers**: Event subscribers to handle Doctrine events

## Key Components

### 1. AclServiceProvider

Located at `src/AclServiceProvider.php`, this is the main service provider that bootstraps the ACL library by:
- Publishing configuration files
- Registering the necessary services
- Setting up Laravel's Gate with permission definitions
- Registering the Doctrine event subscribers

```php
// Defines permissions in Laravel's Gate
protected function definePermissions(Gate $gate, PermissionManager $manager)
{
    foreach ($manager->getPermissionsWithDotNotation() as $permission) {
        $gate->define($permission, function (HasPermissions $user) use ($permission) {
            return $user->hasPermissionTo($permission);
        });
    }
}
```

### 2. Manager Classes

The library uses manager classes to handle different ACL components:

- **PermissionManager** (`src/Permissions/PermissionManager.php`): Handles permissions, converts them to dot notation for Laravel Gate, and determines the appropriate permission driver.
- **Manager** (`src/Manager.php`): Abstract base class for all managers, implementing the driver pattern.

### 3. Contracts (Interfaces)

Located in `src/Contracts/`, these interfaces define the core functionality:

- **Permission**: Defines what a permission should implement
- **Role**: Extends HasPermissions, representing a role with permissions
- **Organisation**: Interface for organization entities
- **HasPermissions**: Contract for entities that have permissions
- **HasRoles**: Contract for entities that have roles
- **BelongsToOrganisation**: Contract for entities belonging to an organization

### 4. Implementation Traits

These traits provide implementations of the contract interfaces:

- **HasPermissions** (`src/Permissions/HasPermissions.php`): Implementation for checking permissions
- **HasRoles** (`src/Roles/HasRoles.php`): Implementation for checking roles
- **BelongsToOrganisation** (`src/Organisations/BelongsToOrganisation.php`): Implementation for organization membership

### 5. Permission System

Two permission drivers are available:

- **ConfigPermissionDriver**: Loads permissions from the configuration
- **DoctrinePermissionDriver**: Loads permissions from the database via Doctrine entities

### 6. PHP 8 Attributes for Mappings

The library uses PHP 8 attributes to define relationships between entities:

- **HasRoles**: Defines a ManyToMany relationship with roles
- **HasPermissions**: Defines a ManyToMany relationship with permissions (or a JSON array for config-based permissions)
- **BelongsToOrganisation**: Defines a ManyToOne relationship with an organization
- **BelongsToOrganisations**: Defines a ManyToMany relationship with organizations

### 7. Doctrine Mappings

The library uses Doctrine event subscribers to automatically map ACL relationships:

- `RegisterMappedEventSubscribers` class registers all subscribers with Doctrine's event manager
- `MappedEventSubscriber` is the base class for all event subscribers, now using PHP 8 attributes instead of annotations
- Individual subscribers like `HasRolesSubscriber`, `HasPermissionsSubscriber`, and `BelongsToOrganisationSubscriber` handle specific relationship mappings

### 8. Configuration

The configuration file at `config/acl.php` allows customizing:

- Entity classes for roles, permissions, and organizations
- The permission driver (config or doctrine)
- A list of permissions (when using the config driver)

```php
return [
    'roles' => [
        'entity' => App\Entities\Role::class,
    ],
    'permissions' => [
        'driver' => 'config',
        'entity' => LaravelDoctrine\ACL\Permissions\Permission::class,
        'list'   => [],
    ],
    'organisations' => [
        'entity' => App\Entities\Organisation::class,
    ],
];
```

## How It Works

1. **Bootstrap**: The service provider registers the ACL components with Laravel
2. **Mappings**: When Doctrine loads entity metadata, the event subscribers add the necessary mappings based on PHP 8 attributes
3. **Gate Integration**: Permissions are registered with Laravel's Gate for authorization
4. **Entity Integration**: User entities implement the interfaces and use the traits to gain ACL functionality

## Extending the Library

To extend the library, you can:

1. Create your own entity classes implementing the appropriate interfaces
2. Configure these entity classes in the `acl.php` configuration file
3. Use the provided traits to implement the required functionality
4. Register your permissions either in the config or as Doctrine entities
5. Apply the PHP 8 attributes to your entity properties to define relationships

## Flow Example

When checking if a user has a permission:

1. The `HasPermissions` trait's `hasPermissionTo()` method is called
2. It checks if the user directly has the permission
3. If not, it checks if the user has any roles with the permission
4. The permission manager uses the configured driver to fetch all available permissions
5. Laravel's Gate uses these permission definitions for its `allows()` method

This architecture allows for flexible access control with both role-based and permission-based authorization in Laravel applications using Doctrine ORM.