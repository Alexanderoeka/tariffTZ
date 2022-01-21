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


Route::get('/','Address\AddressController@show')->name('show.addresses');

Route::get('/try','Address\AddressController@try')->name('try');

Route::get('/formaddress','Address\AddressController@formAddress')->name('formaddress');

Route::post('/storeaddress','Address\AddressController@storeAddress')->name('storeaddress');
