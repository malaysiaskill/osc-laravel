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

# Team
Route::get('/dev-team', 'JTKController@DevTeam'); // Senarai DevTeam
Route::get('/dev-team/{id}', 'JTKController@DevTeam'); // DevTeam mengikut PPD
Route::post('/dev-team', 'JTKController@SaveDevTeam'); // Insert, Update DevTeam
Route::post('/dev-team/edit/{id}', 'JTKController@EditDevTeam'); // Edit DevTeam
Route::post('/dev-team/delete/{id}', 'JTKController@DeleteDevTeam'); // Delete DevTeam

# Projek
Route::get('/dev-team/senarai-projek/{ppd}', 'JTKController@SenaraiProjekAll'); // Untuk JPN & PPD
Route::post('/dev-team/projek', 'JTKController@SaveProjek'); // Insert, Update Projek
Route::get('/dev-team/projek/{groupid}', 'JTKController@SenaraiProjek'); // Senarai Projek mengikut Kumpulan DevTeam
Route::post('/dev-team/projek/view/{projekid}', 'JTKController@ViewProjek'); // Projek Detail
Route::post('/dev-team/projek/edit/{projekid}', 'JTKController@EditProjek'); // Edit Projek
Route::post('/dev-team/projek/delete/{projekid}', 'JTKController@DeleteProjek'); // Delete Projek
Route::post('/dev-team/projek/kertas-kerja', 'JTKController@UploadKertasKerja'); // Insert, Update Kertas Kerja
Route::post('/dev-team/projek/padam-kertas-kerja/{filename}', 'JTKController@PadamKertasKerja'); // Padam Kertas Kerja

# Tasks
Route::get('/dev-team/projek/{projekid}/tasks', 'JTKController@SenaraiTask'); // Senarai Tasks
Route::get('/dev-team/projek/task/{taskid}', 'JTKController@DetailTask'); // Detail Task
Route::post('/dev-team/projek/task', 'JTKController@SaveTask'); // Insert, Update Task
Route::post('/dev-team/projek/task-timeline', 'JTKController@SaveTaskTimeline'); // Insert, Update Task Timeline
Route::post('/dev-team/projek/task/edit/{taskid}', 'JTKController@EditTask'); // Edit Task
Route::post('/dev-team/projek/task/delete/{taskid}', 'JTKController@DeleteTask'); // Delete Task
Route::post('/dev-team/projek/task-detail/delete', 'JTKController@DeleteTaskDetail'); // Delete Task Detail

/**

	SMART TEAM

*/

# Team
Route::get('/smart-team', 'JTKController@SmartTeam'); // Senarai SmartTeam
Route::get('/smart-team/{ppd}', 'JTKController@SmartTeam'); // SmartTeam mengikut PPD
Route::get('/smart-team/detail/{team_id}', 'JTKController@DetailSmartTeam'); // Detail SmartTeam
Route::post('/smart-team', 'JTKController@SaveSmartTeam'); // Insert, Update SmartTeam
Route::post('/smart-team/edit/{id}', 'JTKController@EditSmartTeam'); // Edit SmartTeam
Route::post('/smart-team/delete/{id}', 'JTKController@DeleteSmartTeam'); // Delete SmartTeam
Route::post('/smart-team/aktiviti', 'JTKController@SaveAktivitiSmartTeam'); // Insert, Update Aktiviti SmartTeam
Route::post('/smart-team/aktiviti/edit/{xtvtid}', 'JTKController@EditAktivitiSmartTeam'); // Edit Aktiviti SmartTeam
Route::post('/smart-team/aktiviti/delete/{xtvtid}', 'JTKController@PadamAktivitiSmartTeam'); // Padam Aktiviti SmartTeam
Route::get('/smart-team/aktiviti-detail/{xtvtid}', 'JTKController@DetailAktivitiSmartTeam'); // Detail Aktiviti SmartTeam
Route::post('/smart-team/aktiviti/upload-gambar/{xtvtid}', 'JTKController@UploadGambarAktivitiSmartTeam'); // Upload Gambar Aktiviti SmartTeam
Route::post('/smart-team/aktiviti/padam-gambar/{public_id}', 'JTKController@PadamGambarAktivitiSmartTeam'); // Padam Gambar Aktiviti SmartTeam
