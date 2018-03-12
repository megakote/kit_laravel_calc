<?php

use App\City;
use App\Product;
use App\Helpers\DeliveryCalc;
use alfamart24\laravel_tk_kit\Kit;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    $products = Product::all();
    $cites = City::all();
    $mainCity = City::firstOrNew(['name' => 'Москва']);

    $DeliveryCalcHelper = new DeliveryCalc();
    foreach ($products as $product) {
        foreach ($cites as $city) {
            set_time_limit(10);
            $DeliveryCalcHelper->execute($mainCity, $city, $product);
        }
    }

    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

    echo "Выполнялось $time секунд\n";
});



Route::get('/',['uses' =>'IndexController@index', 'as' => 'index']);
Route::post('/execute',['uses' =>'IndexController@execute', 'as' => 'execute']);

