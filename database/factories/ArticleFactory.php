<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Article::class, function (Faker $faker) {
    return [
        'title'     => $title = $faker->words(3, true),
        'abstract'  => $faker->sentences(5, true),
        'body'      => $faker->paragraphs(5, true),
        'is_active' => true,
    ];
});
