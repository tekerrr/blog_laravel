<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Comment::class, function (Faker $faker) {
    return [
        'user_id'    => factory(\App\User::class),
        'article_id' => factory(\App\Article::class),
        'body'       => $faker->sentences(rand(1, 5), true),
        'is_active'  => true,
    ];
});
