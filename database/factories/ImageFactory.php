<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Image::class, function (Faker $faker) {
    $imageables = [
        \App\Article::class,
        \App\User::class,
    ];
    $imageableType = $faker->randomElement($imageables);
    $imageable = factory($imageableType);

    return [
        'path'           => $faker->imageUrl(640, 480),
        'imageable_type' => $imageableType,
        'imageable_id'   => $imageable,
    ];
});

$factory->state(\App\Image::class, 'withoutImageable', function () {
    return [
        'imageable_type' => '',
        'imageable_id'   => '',
    ];
});
