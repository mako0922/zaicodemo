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

Route::get('audjpy','GraphController@audjpy')
      ->middleware('auth');
Route::get('usdjpy','GraphController@usdjpy')
            ->middleware('auth');
Route::get('cadjpy','GraphController@cadjpy')
            ->middleware('auth');
Route::get('nzdjpy','GraphController@nzdjpy')
            ->middleware('auth');
Route::get('chfjpy','GraphController@chfjpy')
            ->middleware('auth');
Route::get('audnzd','GraphController@audnzd')
            ->middleware('auth');

Route::post('onchange','GraphController@onchange')
            ->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
