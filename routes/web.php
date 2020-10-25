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

Route::get('zaico_input','ZaicoInputController@zaico_input')
      ->middleware('auth');

Route::post('zaico_input/register','ZaicoInputController@register')
      ->middleware('auth');

Route::get('class_input','ZaicoClassController@class_input')
      ->middleware('auth');

Route::post('class_input/register','ZaicoClassController@class_register')
      ->middleware('auth');

Route::get('manufacturer_input','ZaicoManufacturerController@manufacturer_input')
      ->middleware('auth');

Route::post('manufacturer_input/register','ZaicoManufacturerController@manufacturer_register')
      ->middleware('auth');

Route::get('status_input','ZaicoStatusController@status_input')
      ->middleware('auth');

Route::post('status_input/register','ZaicoStatusController@status_register')
      ->middleware('auth');

Route::get('supplier_input','ZaicoSupplierController@supplier_input')
      ->middleware('auth');

Route::post('supplier_input/register','ZaicoSupplierController@supplier_register')
      ->middleware('auth');

Route::get('part_info','ZaicoPartInfoController@part_info')
      ->middleware('auth');

Route::post('part_info/register','ZaicoPartInfoController@part_info_register')
      ->middleware('auth');

Route::get('part_info_select','ZaicoController@part_info_select')
      ->middleware('auth');

Route::get('part_update','ZaicoPartInfoUpdateController@part_update')
      ->middleware('auth');

Route::post('part_update/register','ZaicoPartInfoUpdateController@part_update_register')
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

Route::get('zaico_log_registration','ZaicoLogRegistrationController@zaico_log_registration')
      ->middleware('auth');

Route::post('zaico_log/registration','ZaicoLogRegistrationController@zaico_log_input_registration')
      ->middleware('auth');

Route::post('zaico_log_registration/register','ZaicoLogRegistrationController@zaico_log_registration_register')
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

Route::get('storage_input','ZaicoStorageController@storage_input')
      ->middleware('auth');

Route::post('storage_input/register','ZaicoStorageController@storage_register')
      ->middleware('auth');

Route::get('used_info','ZaicoUsedInfoController@used_info')
      ->middleware('auth');

Route::post('used_info/register','ZaicoUsedInfoController@used_info_register')
      ->middleware('auth');

Route::post('table_item_delete','ZaicoController@table_item_delete')
      ->middleware('auth');

Route::post('csv_download','ZaicoController@csv_download')
      ->middleware('auth');

Route::post('csv_log_download','ZaicoController@csv_log_download')
      ->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
