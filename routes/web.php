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

Route::get('/', 'HomeController@Index');
Auth::routes();
Route::get('/home', 'HomeController@Index');
Route::get('/access-denied', 'HomeController@AccessDenied');

/**
	
	Pentadbir Sistem

*/
Route::group(['middleware' => ['role:administrator']], function()
{
	# Mengawalselia Pengguna
	Route::get('/admin', 'AdminController@Index');
	Route::get('/admin/users', 'AdminController@Users');
});

/**
	Juruteknik Profil
*/
Route::get('/profil', 'JTKController@Profil');
Route::put('/profil', 'JTKController@SaveProfil');
