<?php



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

Route::get('/1', function () {
    echo 123;
    //return view('welcome');
});



Route::get('/',['uses' =>'IndexController@index', 'as' => 'index']);
Route::post('/execute',['uses' =>'IndexController@execute', 'as' => 'execute']);