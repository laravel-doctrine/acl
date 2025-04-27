<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;
use Workbench\App\Entities\UserSingleOrg;

/** phpcs:disable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable */
/** @var Factory $factory */

$factory->define(UserSingleOrg::class, static function (Generator $faker, array $attributes = []) {
    return [
        'name' => $attributes['name'] ?? $faker->name(),
        'email' => $attributes['email'] ?? $faker->safeEmail,
        'password' => 'password',
    ];
});
