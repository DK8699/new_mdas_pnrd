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

/*Route::get('/', function () {
   return view('auth.login');
});*/

Route::get('/', function () {
   return view('public/index');
});

Route::get('/assam-map', function () {
    return view('assam-map');
})->name('assam-map');

Route::get('/msg_sent/{mobile}/{msg}','Admin\Grievance\AdminGrievanceController@msg_sent');
Auth::routes();

//Route::post('/loginwithdata', 'Auth\RemoteLoginController@loginWithUsername')->name('loginwithdata');

Route::get('/dashboard', 'UserDashboardController@index')->name('dashboard');

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/zaction/zp', 'Zaction\ActionController@updateZPPriEmail');
//Route::get('/zaction/ap', 'Zaction\ActionController@updateAPPriEmail');
//Route::get('/zaction/gp', 'Zaction\ActionController@updateGPPriEmail');

//*******************************Update Password-------------------------------------------------
Route::post('update_password', 'ChangePasswordController@updatePassword')->name('update_password');
//*******************************Update Profile-------------------------------------------------
Route::post('update_profile', 'ChangePasswordController@updateProfile')->name('update_profile');
//*******************************Update Profile Picture-------------------------------------------------
Route::post('update_profile_pic', 'ChangePasswordController@updateProfilePic')->name('update_profile_pic');
//*******************************User's Change Password page**************************************
Route::get('UsersManagement/change_password', 'ChangePasswordController@changePassword')->name('UsersManagement.change_password');
Route::get('UsersManagement/user_management', 'ChangePasswordController@user_management')->name('UsersManagement.user_management');
Route::get('UsersManagement/user_management/statusUser', 'ChangePasswordController@statusUser')->name('UsersManagement.user_management.statusUser');
//*******************************User Management-------------------------------------------------
Route::get('UsersManagement/user_dashboard', 'ChangePasswordController@user_dashboard')->name('UsersManagement.user_dashboard');

Route::get('UsersManagement/da_user_management', 'ChangePasswordController@ZpUser')->name('UsersManagement.da_user_management');
Route::get('UsersManagement/aa_user_management', 'ChangePasswordController@ApUser')->name('UsersManagement.aa_user_management');
Route::get('UsersManagement/ga_user_management', 'ChangePasswordController@GpUser')->name('UsersManagement.ga_user_management');
//*******************************User's  Profile-------------------------------------------------
Route::get('UsersManagement/profile', 'ChangePasswordController@profile')->name('UsersManagement.profile');


Route::get('panchayat_profile', 'PanchayatController@index')->name('panchayat_profile');
Route::post('panchayat_profile', 'PanchayatController@submit')->name('panchayat_profile');

/*----------- ADMIN --------------------*/
include_once ('web_admin.php');
/*----------- ADMIN ENDED --------------*/

/*------ SIX FINANCE USER ROUTES -------*/
include_once ('web_six_finance.php');
/*------- SIX FINANCE ROUTES -----------*/

/*----------- PRIs ---------------------*/
include_once ('pris/web_pris_users.php');
/*----------- PRIs ---------------------*/

/*----------- Osr ---------------------*/
include_once ('Osr/web_osr.php');

include_once ('Osr/web_osr_app.php');
/*----------- Osr ---------------------*/

/*----------- UC ---------------------*/
include_once ('Uc/web_uc.php');
/*----------- UC ---------------------*/

/*----------- Common ---------------------*/
include_once ('Common/web_common.php');
/*----------- Common ---------------------*/

/*----------- Court Cases ---------------------*/
include_once ('web_court_cases.php');
/*----------- Court Cases ---------------------*/

/*----------- Court Case District ---------------------*/
include_once ('CourtCases/web_court_cases.php');
/*-----------Court Case District ---------------------*/

/*----------- Grievance System (12-05-2020)---------------------*/
include_once ('Grievance/web_griev.php');
/*-----------Grievance System ---------------------*/

/*----------- Training(17-09-2020)---------------------*/
include_once ('Training/web_training.php');
/*-----------Grievance System ---------------------*/

/*----------- public ---------------------*/
include_once ('web_public.php');
/*----------- public ---------------------*/



