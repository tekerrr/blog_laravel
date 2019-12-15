<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Page::class, function (Faker $faker) {
    return [
        'title'     => $title = $faker->words(2, true),
        'body'      => $faker->paragraphs(5, true),
        'is_active' => true,
    ];
});
