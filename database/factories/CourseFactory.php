<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Courses\Course;
use App\User;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'name' => $faker->word,
        'description' => $faker->sentence,
        'day' => $faker->numberBetween(0, 6),
        'time' => $faker->dateTime,
    ];
});
