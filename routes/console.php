<?php

use App\Product;
use App\City;
use App\Helpers\DeliveryCalc;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('t', function () {
    $products = Product::all();
    $cites = City::all();
    $mainCity = City::firstOrNew(['name' => 'Москва']);

    foreach ($products as $product) {
        foreach ($cites as $city) {
            dump(new DeliveryCalc($mainCity, $city, $product));
        }
    }

    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

    echo "Выполнялось $time секунд\n";
});
