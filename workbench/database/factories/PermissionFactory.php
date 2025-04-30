<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Faker\Generator;
use LaravelDoctrine\ACL\Permissions\Permission;
use LaravelDoctrine\ORM\Testing\Factory;

/** phpcs:disable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable */
/** @var Factory $factory */

$factory->define(Permission::class, static function (Generator $faker, array $attributes = []) {
    return [
        'name' => $attributes['name'] ?? $faker->unique()->word . '-' . $faker->unique()->word,
    ];
});

$factory->defineAs(Permission::class, 'view', static function () {
    return ['name' => 'view'];
});

$factory->defineAs(Permission::class, 'edit', static function () {
    return ['name' => 'edit'];
});

$factory->defineAs(Permission::class, 'delete', static function () {
    return ['name' => 'delete'];
});
