<?php

use Workbench\App\Entities\Organisation;

/** @var Factory $factory */
$factory->define(Organisation::class, function(Faker\Generator $faker, array $attributes) {
    return [
        'name' => $attributes['name'] ?? $faker->unique()->company,
    ];
});
