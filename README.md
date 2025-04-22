<p align="center">
    <img src="https://placehold.co/10x10/337ab7/337ab7.png" width="100%" height="15px">
    <img width="450px" src="https://github.com/laravel-doctrine/acl/blob/2.0.x/docs/banner.png"/>
</p>

Laravel Doctrine ACL
====================

Laravel Doctrine ACL is a package that provides RBAC (Role-Based Access Control) functionality for Laravel applications using Doctrine. It allows you to manage roles, permissions, and organisations, and seamlessly integrates with Laravel's Authorization system.

[![Build Status](https://github.com/ScholarshipOwl/laravel-doctrine-acl/actions/workflows/php.yml/badge.svg)](https://github.com/ScholarshipOwl/laravel-doctrine-acl/actions)
[![Code Coverage](https://codecov.io/gh/laravel-doctrine/acl/graph/badge.svg?token=3CpQzDXOWX)](https://codecov.io/gh/laravel-doctrine/acl)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%201-brightgreen.svg)](https://img.shields.io/badge/PHPStan-level%201-brightgreen.svg)
[![Documentation](https://readthedocs.org/projects/laravel-doctrine-acl-official/badge/?version=latest)](https://laravel-doctrine-acl-official.readthedocs.io/en/latest/)
[![Packagist Downloads](https://img.shields.io/packagist/dd/laravel-doctrine/acl)](https://packagist.org/packages/laravel-doctrine/acl)

Installation
------------

Via composer:

```bash
composer require laravel-doctrine/acl
```

The ServiceProvider and Facades are autodiscovered.

Publish the configuration:

```bash
php artisan vendor:publish --tag="config" --provider="LaravelDoctrine\ACL\AclServiceProvider"
```

Documentation
-------------

Full documentation at https://laravel-doctrine-acl.readthedocs.io/en/latest/index.html
or in the docs directory.

Versions
--------

* Version 2 supports DBAL ^4.0, ORM ^3.0, and PHP 8.2.
* Version 1 supports Laravel 6 - 11, DBAL ^2.0, ORM ^2.0, and PHP ^5.5 - ^8.0.
