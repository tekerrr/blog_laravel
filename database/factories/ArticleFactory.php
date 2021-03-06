<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Article::class, function (Faker $faker) {
    return [
        'title'     => $title = $faker->words(3, true),
        'abstract'  => $faker->sentences(3, true),
        'body'      => $faker->paragraphs(5, true),
        'is_active' => true,
    ];
});
