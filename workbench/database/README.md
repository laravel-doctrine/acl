# Laravel Doctrine ORM Entity Factories

This guide explains how to create and use entity factories for Doctrine entities in the workbench. Factories are essential for generating test data and seeding your database in a consistent, maintainable way.

## Defining an Entity Factory

To define a factory for an entity, use the `$factory->define()` method in a factory file (e.g., `UserEntityFactory.php`).

```php
$factory->define(App\Entities\User::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'emailAddress' => $faker->email
    ];
});
```
- Use Doctrine entity property names (not database column names).
- You can define multiple types for the same entity using `defineAs`:

```php
$factory->defineAs(App\Entities\User::class, 'admin', function(Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'emailAddress' => $faker->email,
        'isAdmin' => true
    ];
});
```

## Using Factories in Seeds and Tests

After defining factories, you can generate entities for tests or seeds using the `entity()` helper or the factory directly.

- **Create (persist) a single entity:**
  ```php
  entity(App\Entities\User::class)->create();
  // or
  $factory->of(App\Entities\User::class)->create();
  ```

- **Make (do not persist) a single entity:**
  ```php
  entity(App\Entities\User::class)->make();
  ```

- **Create multiple entities:**
  ```php
  entity(App\Entities\User::class, 3)->create();
  // or
  $factory->of(App\Entities\User::class)->times(3)->create();
  ```

- **Create a specific type:**
  ```php
  entity(App\Entities\User::class, 'admin')->create();
  ```

## Passing Extra Attributes

You can override default attributes by passing an array:

```php
$factory->define(App\Entities\User::class, function(Faker\Generator $faker, array $attributes) {
    return [
        'name' => $attributes['name'] ?? $faker->name,
        'emailAddress' => $faker->email
    ];
});

$user = entity(App\Entities\User::class)->make(['name' => 'Taylor']);
```

## Notes
- The `entity()` helper returns an `Illuminate\Support\Collection` if you request multiple entities.
- Use `->make()` to get an instance without saving, or `->create()` to persist to the database.
- Always use property names as defined in your Doctrine entity.

## References
- [Official Docs: Testing - Entity Factories](https://laravel-doctrine-orm-official.readthedocs.io/en/latest/testing.html)
