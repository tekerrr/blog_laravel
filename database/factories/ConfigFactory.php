<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Config::class, function (Faker $faker) {
    return [
        'key'   => $faker->word,
        'value' => $faker->word,
    ];
});
