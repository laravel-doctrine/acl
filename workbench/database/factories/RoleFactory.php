<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;
use Workbench\App\Entities\Role;

/** phpcs:disable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable */
/** @var Factory $factory */

$factory->define(Role::class, static function (Generator $faker, array $attributes = []) {
    return [
        'name' => $attributes['name'] ?? $faker->unique()->word . '-' . $faker->unique()->word,
    ];
});

$factory->defineAs(Role::class, 'admin', static function () {
    return ['name' => 'admin'];
});

$factory->defineAs(Role::class, 'user', static function () {
    return ['name' => 'user'];
});
