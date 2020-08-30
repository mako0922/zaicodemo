<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('zaico_home','ZaicoController@zaico_home')
      ->middleware('auth');

Route::get('zaico_input','ZaicoController@zaico_input')
      ->middleware('auth');

Route::post('zaico_input/register','ZaicoController@register')
      ->middleware('auth');

Route::get('class_input','ZaicoController@class_input')
      ->middleware('auth');

Route::post('class_input/register','ZaicoController@class_register')
      ->middleware('auth');

Route::get('manufacturer_input','ZaicoController@manufacturer_input')
      ->middleware('auth');

Route::post('manufacturer_input/register','ZaicoController@manufacturer_register')
      ->middleware('auth');

Route::get('part_info','ZaicoController@part_info')
      ->middleware('auth');

Route::post('part_info/register','ZaicoController@part_info_register')
      ->middleware('auth');

Route::get('zaico_list','ZaicoController@zaico_list')
      ->middleware('auth');

Route::post('zaico_input/arrival','ZaicoController@zaico_input_arrival')
      ->middleware('auth');

Route::post('zaico_input/utilize','ZaicoController@zaico_input_utilize')
      ->middleware('auth');

Route::get('zaico_log','ZaicoController@zaico_log')
      ->middleware('auth');

Route::post('onchange_log','ZaicoController@onchange_log')
            ->middleware('auth');

Route::post('onchange_list','ZaicoController@onchange_list')
            ->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
