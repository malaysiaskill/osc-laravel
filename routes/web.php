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
	/**
		Super Administrator Only
	*/
	Route::get('/admin/packages', 'AdminController@Packages');
	Route::get('/admin/packages/{id}/activate', 'AdminController@ActivatePackages');
	Route::get('/admin/packages/{id}/deactivate', 'AdminController@DeactivatePackages');
	Route::get('/admin/packages/{id}/delete', 'AdminController@DeletePackages');

	# Mengawalselia Pengguna
	Route::get('/admin/users', 'AdminController@Users');
	Route::post('/admin/users', 'AdminController@SaveUser');
	Route::post('/admin/users/getuser/{id}', 'AdminController@GetUser');
	Route::get('/admin/users/{id}/delete', 'AdminController@DeleteUser');
});

/**
	Juruteknik Profil
*/
Route::get('/profil', 'JTKController@Profil');
Route::put('/profil', 'JTKController@SaveProfil');
