<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
      'name' => $faker->text(30),
      'weight' => $faker->numberBetween(1, 50),
      'length' => $faker->numberBetween(30, 300),
      'width' => $faker->numberBetween(20, 500),
      'height' => $faker->numberBetween(40, 200),
      'price' => $faker->numberBetween(90, 3000)
    ];
});