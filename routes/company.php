<?php

Auth::routes();

//企业
Route::get('/', ['as' => 'company.index.index', 'uses' => 'Company\IndexController@index']);

Route::match(['post','get'], '/login',['as'=>'company.index.login', 'uses'=>'Company\IndexController@login']);
Route::match(['post','get'], '/logout',['as'=>'company.index.logout', 'uses'=>'Company\IndexController@logout']);
Route::match(['post','get'], '/find',['as'=>'company.index.find', 'uses'=>'Company\IndexController@find']);
Route::get('/findOk',['as'=>'company.index.findOk', 'uses'=>'Company\IndexController@findOk']);
Route::match(['post','get'], '/findPassword',['as'=>'company.index.findPassword', 'uses'=>'Company\IndexController@findPassword']);
Route::match(['post','get'], '/company/changePassword',['as'=>'company.user.changePassword', 'uses'=>'Company\CompanyController@changePassword']);
Route::match(['post','get'], '/company/changeEmail',['as'=>'company.user.changeEmail', 'uses'=>'Company\CompanyController@changeEmail']);
Route::get('/company/index', ['as'=>'company.user.index', 'uses'=>'Company\CompanyController@index']);
Route::get('/company/account', ['as'=>'company.user.account', 'uses'=>'Company\CompanyController@account']);


Route::match(['post','get'], '/info',['as'=>'company.index.info', 'uses'=>'Company\CompanyController@info']);

//job
Route::get('/job', ['as'=>'company.job.index', 'uses'=>'Company\JobController@index']);
Route::post('/job/copy', ['as'=>'company.job.copy', 'uses'=>'Company\JobController@copy']);
Route::match(['post','get'], '/job/input/{job_id?}',['as'=>'company.job.input', 'uses'=>'Company\JobController@input']);
Route::post('/job/delete', ['as'=>'company.job.delete', 'uses'=>'Company\JobController@deleted']);

//应募
Route::match(['get'], '/record/index/{type}',['as'=>'company.record.index', 'uses'=>'Company\JobRecordController@index']);

Route::match(['get'], '/record/user_info/{record_id?}',['as'=>'company.record.user_info', 'uses'=>'Company\JobRecordController@user_info']);

Route::match(['get'], '/record/resume_info/{user_id?}',['as'=>'company.record.resume_info', 'uses'=>'Company\JobRecordController@resume_info']);

Route::match(['get','post'], '/record/{type}/user/{job_id?}',['as'=>'company.record.user', 'uses'=>'Company\JobRecordController@recordUserList']);
Route::match(['post'], '/record/memoList',['as'=>'company.record.memoList', 'uses'=>'Company\JobRecordController@memo_list']);
Route::match(['post'], '/record/statusListId',['as'=>'company.record.statusListId', 'uses'=>'Company\JobRecordController@statusListId']);

Route::match(['post'], '/record/memoAdd',['as'=>'company.record.memoAdd', 'uses'=>'Company\JobRecordController@memo_add']);
Route::match(['get', 'post'], '/record/statusList/{record_id?}',['as'=>'company.record.statusList', 'uses'=>'Company\JobRecordController@status_list']);
Route::match(['post'], '/record/statusAdd',['as'=>'company.record.statusAdd', 'uses'=>'Company\JobRecordController@status_add']);
Route::match(['post'], '/record/read',['as'=>'company.record.read', 'uses'=>'Company\JobRecordController@read']);

Route::match(['get'], '/scout/user/{job_id}',['as'=>'company.scout.user', 'uses'=>'Company\JobRecordController@scoutUser']);
Route::match(['post'], '/scout/selectUser',['as'=>'company.scout.selectUser', 'uses'=>'Company\JobRecordController@selectUser']);

Route::match(['get'], '/record/video/{record_id}',['as'=>'company.record.video', 'uses'=>'Company\JobRecordController@video']);

