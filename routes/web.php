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

Route::get('books/{id}', 'BooksController@show');
Route::post('books', 'BooksController@store');
Route::delete('books/{book}', 'BooksController@destroy');
Route::post('books/{book}/orders', 'BooksOrdersController@store');


Route::get('home', 'HomeController@index')->name('home');

Auth::routes();
