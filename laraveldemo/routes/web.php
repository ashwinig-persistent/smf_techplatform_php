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
    return view('welcome');
});*/

Route::get('/','PagesController@index');

Route::get('/about','PagesController@about');

Route::get('/services','PagesController@services');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(
    ['middleware'=>'auth'],
    function(){
    Route::get('/admin',
    ['as'=>'admin.index',
     'uses'=> function(){
        return view('admin.index');
    }
   ]);
   Route::resource('role','RoleController');
   Route::resource('organisation','OrganisationController');
   Route::resource('users','UserController');
   Route::resource('state','StateController');
   Route::resource('jurisdiction','JurisdictionController');
   Route::resource('district','DistrictController');
   Route::resource('taluka','TalukaController');
   Route::resource('cluster','ClusterController');
   Route::resource('village','VillageController');
   Route::get('/tenantroles','RoleController@getTenantRoles');

   });


Route::get('/getRoles','RoleController@getOrgRoles');
Route::get('/getJurisdiction','StateController@getJurisdiction');
Route::get('/getLevel','UserController@getLevel');
Route::get('/getRoles','RoleController@getOrgRoles');
Route::get('/getJuris','RoleController@getJuris');
Route::get('/getJidandLevel','TalukaController@getJidandLevel');
Route::get('/populateData','TalukaController@populateData');