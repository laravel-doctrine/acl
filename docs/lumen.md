# Installation in Lumen 6+

To set up Laravel Doctrine ACL in Lumen, we need some additional steps.

Install this package with composer:

```
composer require laravel-doctrine/acl
```

After updating composer, open `bootstrap/app.php` and register the Service Provider after `LaravelDoctrine\ORM\DoctrineServiceProvider::class`

```php
$app->register(LaravelDoctrine\ACL\AclServiceProvider::class);
```

### Config

If you want to overrule the Doctrine config. You will have to create a `config/acl.php` file and copy the contents from the package config.
