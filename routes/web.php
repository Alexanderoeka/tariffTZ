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

// Роут к главной странице. Страница вывода всех таблиц адресов(данных)
Route::get('/','Address\AddressController@show')->name('show.addresses');


// Роут к форме ввода адресса
Route::get('/formaddress','Address\AddressController@formAddress')->name('formaddress');

// Роут сохранения данных из формы 'formaddress'
Route::post('/storeaddress','Address\AddressController@storeAddress')->name('storeaddress');
