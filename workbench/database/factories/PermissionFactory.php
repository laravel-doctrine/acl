<?php

namespace Workbench\Database\Factories;

use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;
use LaravelDoctrine\ACL\Permissions\Permission;

/** @var Factory $factory */
$factory->define(Permission::class, static function (Generator $faker, array $attributes = []) {
    return [
        'name' => $attributes['name'] ?? $faker->unique()->word . '-' . $faker->unique()->word,
    ];
});

$factory->defineAs(Permission::class, 'view', static function () {
    return [
        'name' => 'view',
    ];
});

$factory->defineAs(Permission::class, 'edit', static function () {
    return [
        'name' => 'edit',
    ];
});

$factory->defineAs(Permission::class, 'delete', static function () {
    return [
        'name' => 'delete',
    ];
});
