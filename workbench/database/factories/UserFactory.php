<?php

namespace Workbench\Database\Factories;

use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;
use Workbench\App\Entities\User;

/** @var Factory $factory */
$factory->define(User::class, static function (Generator $faker, array $attributes = []) {
    return [
        'name' => $faker->name(),
        'email' => $faker->safeEmail,
        'password' => 'password',
    ];
});

$factory->defineAs(User::class, 'test', static function (Generator $faker, array $attributes = []) {
    return [
        'name' => 'test',
        'email' => 'test@test.tld',
        'password' => 'password',
    ];
});
