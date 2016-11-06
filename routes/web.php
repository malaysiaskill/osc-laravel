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
	
	PENTADBIR SISTEM

*/
Route::group(['middleware' => ['role:administrator']], function()
{
	/**
		Akses bagi Pentadbir Tertinggi Sahaja
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

	PROFIL JURUTEKNIK

*/
Route::get('/profil', 'JTKController@Profil');
Route::get('/avatar', 'JTKController@Avatar');
Route::get('/avatar/{id}', 'JTKController@Avatar');
Route::post('/avatar/delete', 'JTKController@DeleteAvatar');
Route::post('/avatar/upload', 'JTKController@UploadAvatar');
Route::put('/profil', 'JTKController@SaveProfil');

/**

	DEVELOPMENT TEAM

*/
Route::get('/dev-team', 'JTKController@DevTeam');
Route::post('/dev-team', 'JTKController@SaveDevTeam');
Route::get('/dev-team/{id}', 'JTKController@DevTeam');
Route::post('/dev-team/edit/{id}', 'JTKController@getDevTeam');
Route::post('/dev-team/delete/{id}', 'JTKController@DeleteDevTeam');
Route::post('/dev-team/projek', 'JTKController@SaveProjek');
Route::get('/dev-team/projek/{groupid}', 'JTKController@SenaraiProjek');
Route::get('/dev-team/projek/{groupid}/{projekid}', 'JTKController@DetailProjek');
Route::post('/dev-team/projek/view/{projekid}', 'JTKController@ViewProjek');
Route::post('/dev-team/projek/edit/{projekid}', 'JTKController@EditProjek');
Route::post('/dev-team/projek/delete/{projekid}', 'JTKController@DeleteProjek');
Route::post('/dev-team/projek/kertas-kerja', 'JTKController@UploadKertasKerja');
Route::post('/dev-team/projek/padam-kertas-kerja/{filename}', 'JTKController@PadamKertasKerja');

