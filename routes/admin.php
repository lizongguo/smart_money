<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::get('/', ['as' => 'admin.index', 'uses' => 'Admin\IndexController@index']);
#登录退出
Route::get('/logout', ['as' => 'admin.logout', 'uses' => 'Admin\LoginController@logout']);
Route::match(['post','get'], '/login', ['as' => 'admin.login', 'uses' => 'Admin\LoginController@login']);

Route::get('/console', ['as'=>'console.index', 'uses'=>'Admin\IndexController@console']);

//账号管理
Route::match(['post','get'], '/account', ['as'=>'account.index', 'uses'=>'Admin\AccountController@index']);
Route::match(['post','get'], '/account/changePassword', ['as'=>'account.changePwd', 'uses'=>'Admin\AccountController@changePwd']);

/**
 * 权限管理
 */
Route::get('/permissions', ['as'=>'permissions.index', 'uses'=>'Admin\PermissionsController@index']);
Route::get('/permissions/items', ['as'=>'permissions.items', 'uses'=>'Admin\PermissionsController@items']);
Route::match(['post','get'], '/permissions/input/{id?}',['as'=>'permissions.input', 'uses'=>'Admin\PermissionsController@input']);
Route::get('/permissions/detail/{id}', ['as'=>'permissions.detail', 'uses'=>'Admin\PermissionsController@detail']);
Route::get('/permissions/delete/{id}', ['as'=>'permissions.delete', 'uses'=>'Admin\PermissionsController@deleted']);

/**
 * 角色管理
 */
Route::get('/roles', ['as'=>'roles.index', 'uses'=>'Admin\RolesController@index']);
Route::get('/roles/items', ['as'=>'roles.items', 'uses'=>'Admin\RolesController@items']);
Route::match(['post','get'], '/roles/input/{id?}',['as'=>'roles.input', 'uses'=>'Admin\RolesController@input']);
Route::get('/roles/detail/{id}', ['as'=>'roles.detail', 'uses'=>'Admin\RolesController@detail']);
Route::get('/roles/delete/{id}', ['as'=>'roles.delete', 'uses'=>'Admin\RolesController@deleted']);

/**
 * 后台管理员
 */
Route::get('/admins', ['as'=>'admins.index', 'uses'=>'Admin\AdminsController@index']);
Route::get('/admins/items', ['as'=>'admins.items', 'uses'=>'Admin\AdminsController@items']);
Route::match(['post','get'], '/admins/input/{id?}',['as'=>'admins.input', 'uses'=>'Admin\AdminsController@input']);
Route::get('/admins/detail/{id}', ['as'=>'admins.detail', 'uses'=>'Admin\AdminsController@detail']);
Route::get('/admins/delete/{id}', ['as'=>'admins.delete', 'uses'=>'Admin\AdminsController@deleted']);

/**
 * 基金管理
 */
Route::get('/fund', ['as'=>'fund.index', 'uses'=>'Admin\FundController@index']);
Route::get('/fund/items', ['as'=>'fund.items', 'uses'=>'Admin\FundController@items']);
Route::match(['post','get'], '/fund/input/{id?}',['as'=>'fund.input', 'uses'=>'Admin\FundController@input']);
Route::get('/fund/delete/{id}', ['as'=>'fund.delete', 'uses'=>'Admin\FundController@deleted']);

/**
 * 菜单管理
 */
Route::get('/menu', ['as'=>'menu.index', 'uses'=>'Admin\MenuController@index']);
Route::get('/menu/items', ['as'=>'menu.items', 'uses'=>'Admin\MenuController@items']);
Route::match(['post','get'], '/menu/input/{id?}',['as'=>'menu.input', 'uses'=>'Admin\MenuController@input']);
Route::get('/menu/delete/{id}', ['as'=>'menu.delete', 'uses'=>'Admin\MenuController@deleted']);
Route::post('/menu/saveOrder', ['as'=>'menu.saveOrder', 'uses'=>'Admin\MenuController@saveOrder']);

/**
 * 设置
 */
Route::match(['post','get'], '/setting/system', ['as'=>'setting.system', 'uses'=>'Admin\SettingController@system']);//系统设置
Route::post('/setting/site', ['as'=>'setting.site', 'uses'=>'Admin\SettingController@site']);//站点设置
Route::post('/setting/save/{category}', ['as'=>'setting.save', 'uses'=>'Admin\SettingController@save']);//系统设置保存