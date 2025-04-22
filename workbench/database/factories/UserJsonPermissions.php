<?php

namespace Workbench\Database\Factories;

use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;
use Workbench\App\Entities\UserJsonPermissions;

/** @var Factory $factory */
$factory->define(UserJsonPermissions::class, static function (Generator $faker, array $attributes = []) {
    return [
        'name' => $attributes['name'] ?? $faker->name(),
        'email' => $attributes['email'] ?? $faker->safeEmail,
        'password' => 'password',
    ];
});
