<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Page::class, function (Faker $faker) {
    return [
        'title'     => $title = $faker->words(2, true),
        'body'      => $faker->paragraphs(5, true),
        'is_active' => true,
    ];
});
