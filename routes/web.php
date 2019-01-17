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
    Route::get('pse/beneficiary', 'BeneficiaryController@index')->name('admin.pse.beneficiary.index');

    Route::get('devel', 'DevelController@index')->name('admin.devel.code');

});

Route::group(['prefix'=>'pse'],function(){
  Route::get('beneficiary', 'BeneficiaryController@index')->name('pse.beneficiary.index');
  Route::get('beneficiary/{identification}', 'BeneficiaryController@load')->name('pse.beneficiary.load');



});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');







