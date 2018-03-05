<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
      'name' => $faker->text(30),
      'weight' => $faker->numberBetween(1, 50),
      'length' => $faker->numberBetween(30, 100),
      'width' => $faker->numberBetween(20, 300),
      'height' => $faker->numberBetween(20, 100),
      'price' => $faker->numberBetween(90, 3000)
    ];
});