# Laravel Doctrine ACL

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sowl/laravel-doctrine-acl.svg?style=flat-square)](https://packagist.org/packages/sowl/laravel-doctrine-acl)
[![Build Status](https://github.com/sowl/laravel-doctrine-acl/actions/workflows/ci.yml/badge.svg)](https://github.com/sowl/laravel-doctrine-acl/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/sowl/laravel-doctrine-acl.svg?style=flat-square)](https://packagist.org/packages/sowl/laravel-doctrine-acl)
[![License](https://img.shields.io/packagist/l/sowl/laravel-doctrine-acl.svg?style=flat-square)](LICENSE.md)

Fork of the [laravel-doctrine/acl](https://github.com/laravel-doctrine/acl) package (no longer active).

---

ACL functionality for Laravel powered by Doctrine.

---

## Summary

Laravel Doctrine ACL is a package that provides ACL functionality for Laravel applications using Doctrine. It allows you to manage roles, permissions, and organisations, and seamlessly integrates with Laravel's Authorization system.

## Features

- User can have Permissions
- User can have Roles
- User and Roles can have Permissions
- User can belong to Organisation(s)
- Seamless integration with Laravel's Authorization system
- PHP 8.2+ with Attributes support (annotations removed)

## Installation

```bash
composer require sowl/laravel-doctrine-acl
```

## Versions

Version | Supported Laravel Versions
:-------|:------
 ^1.5   | 11.x
 ^1.6   | 12.x

## Quick Start

1. Publish the configuration:
   ```bash
   php artisan vendor:publish --tag="config" --provider="LaravelDoctrine\\ACL\\AclServiceProvider"
   ```
2. Configure your entities and relationships using PHP 8 attributes (see examples in the docs).
3. Use the built-in traits and contracts to add ACL features to your User, Role, Permission, and Organisation entities.

## Documentation

Full documentation is available in the [`docs/`](./docs) folder.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details and a code of conduct.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.