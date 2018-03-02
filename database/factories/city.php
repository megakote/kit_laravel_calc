<?php

use Faker\Generator as Faker;

$factory->define(App\City::class, function (Faker $faker) {
    return [
      'name' => $faker->city,
      'zone' => $faker->numberBetween(3000, 300000),
    ];
});
