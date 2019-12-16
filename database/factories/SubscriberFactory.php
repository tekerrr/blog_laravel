<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Subscriber::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
    ];
});
