<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Subscriber::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
    ];
});
