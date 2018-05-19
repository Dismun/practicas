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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/adminuser','AdminUserController@index')->name('adminuser')->middleware('auth');

Route::post('/sumarolausuario_ajax','AdminUserController@meterol_ajax')->name('sumarolausuario_ajax')->middleware('auth');

Route::post('/quitarolausuario_ajax','AdminUserController@quitarol_ajax')->name('quitarolausuario_ajax')->middleware('auth');

Route::post('/actualizausuario_ajax','AdminUserController@actualizausuario_ajax')->name('actualizausuario_ajax')->middleware('auth');

Route::post('/borrausuario_ajax','AdminUserController@borrausuario_ajax')->name('borrausuario_ajax')->middleware('auth');