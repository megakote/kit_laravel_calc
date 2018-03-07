<?php

use App\Product;
use App\Jobs\GetProductsDeliveryPrice;

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
    dispatch(new GetProductsDeliveryPrice($products));
});
