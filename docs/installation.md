# Installation in Laravel 6+

Install this package with composer:

```
composer require laravel-doctrine/acl
```

After updating composer, add the ServiceProvider to the providers array in `config/app.php`

```php
LaravelDoctrine\ACL\AclServiceProvider::class,
```

To publish the config use:

```php
php artisan vendor:publish --tag="config" --provider="LaravelDoctrine\ACL\AclServiceProvider"
```
