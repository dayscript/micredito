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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('pse/beneficiary', function(){
      return view('admin.beneficiary');
    })->name('admin.pse.beneficiary');
});

Route::get('pse/beneficiary', function(){
  return view('pse.beneficiary');
})->name('pse.beneficiary');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
