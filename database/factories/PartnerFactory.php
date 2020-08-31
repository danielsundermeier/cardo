<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Partners\Partner;
use Faker\Generator as Faker;

$factory->define(Partner::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'firstname' => $faker->firstname,
        'lastname' => $faker->lastname,
    ];
});
