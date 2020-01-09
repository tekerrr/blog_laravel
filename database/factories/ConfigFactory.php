<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;

$factory->define(\App\Config::class, function (Faker $faker) {
    return [
        'key' => $faker->word,
        'value' => $faker->word,
    ];
});
