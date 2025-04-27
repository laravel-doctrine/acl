<?php

declare(strict_types=1);

use Faker\Generator;
use Workbench\App\Entities\Organisation;

/** phpcs:disable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable */
/** @var Factory $factory */

$factory->define(Organisation::class, static function (Generator $faker, array $attributes) {
    return [
        'name' => $attributes['name'] ?? $faker->unique()->company,
    ];
});
