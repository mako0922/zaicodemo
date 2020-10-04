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

Route::get('status_input','ZaicoController@status_input')
      ->middleware('auth');

Route::post('status_input/register','ZaicoController@status_register')
      ->middleware('auth');

Route::get('supplier_input','ZaicoController@supplier_input')
      ->middleware('auth');

Route::post('supplier_input/register','ZaicoController@supplier_register')
      ->middleware('auth');

Route::get('part_info','ZaicoController@part_info')
      ->middleware('auth');

Route::post('part_info/register','ZaicoController@part_info_register')
      ->middleware('auth');

Route::get('part_info_select','ZaicoController@part_info_select')
      ->middleware('auth');

Route::get('part_update','ZaicoController@part_update')
      ->middleware('auth');

Route::post('part_update/register','ZaicoController@part_update_register')
      ->middleware('auth');

Route::get('part_delete','ZaicoController@part_delete')
      ->middleware('auth');

Route::post('part_delete/register','ZaicoController@part_delete_register')
      ->middleware('auth');

Route::get('zaico_list','ZaicoController@zaico_list')
      ->middleware('auth');

Route::post('part_list_serch','ZaicoController@part_list_serch')
      ->middleware('auth');

Route::get('part_list_serch','ZaicoController@part_list_serch')
      ->middleware('auth');

Route::get('zaico_input/arrival','ZaicoController@zaico_input_arrival')
      ->middleware('auth');

Route::post('zaico_input/arrival','ZaicoController@zaico_input_arrival')
      ->middleware('auth');

Route::post('zaico_input/utilize','ZaicoController@zaico_input_utilize')
      ->middleware('auth');

Route::post('zaico_input/update','ZaicoController@zaico_input_update')
      ->middleware('auth');

Route::post('zaico_input/delete','ZaicoController@zaico_input_delete')
      ->middleware('auth');

Route::get('zaico_log','ZaicoController@zaico_log')
      ->middleware('auth');

Route::get('zaico_log_delete','ZaicoController@zaico_log_delete')
      ->middleware('auth');

Route::post('zaico_log/delete','ZaicoController@zaico_log_input_delete')
      ->middleware('auth');

Route::post('zaico_log_delete/register','ZaicoController@zaico_log_delete_register')
      ->middleware('auth');

Route::post('zaico_log_serch','ZaicoController@zaico_log_serch')
      ->middleware('auth');

Route::get('zaico_log_serch','ZaicoController@zaico_log_serch')
      ->middleware('auth');

Route::post('onchange_log','ZaicoController@onchange_log')
            ->middleware('auth');

Route::get('onchange_log','ZaicoController@onchange_log')
            ->middleware('auth');

Route::post('onchange_list','ZaicoController@onchange_list')
            ->middleware('auth');
Route::get('onchange_list','ZaicoController@onchange_list')
            ->middleware('auth');

Route::get('storage_input','ZaicoController@storage_input')
      ->middleware('auth');

Route::post('storage_input/register','ZaicoController@storage_register')
      ->middleware('auth');

Route::get('used_info','ZaicoController@used_info')
      ->middleware('auth');

Route::post('used_info/register','ZaicoController@used_info_register')
      ->middleware('auth');

Route::post('table_item_delete','ZaicoController@table_item_delete')
      ->middleware('auth');

Route::post('csv_download','ZaicoController@csv_download')
      ->middleware('auth');

Route::post('csv_log_download','ZaicoController@csv_log_download')
      ->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
