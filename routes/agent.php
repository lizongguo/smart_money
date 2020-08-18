<?php

Auth::routes();

//企业
Route::get('/', ['as' => 'agent.index.index', 'uses' => 'Agent\IndexController@index']);

Route::match(['post','get'], '/login',['as'=>'agent.index.login', 'uses'=>'Agent\IndexController@login']);
Route::match(['post','get'], '/logout',['as'=>'agent.index.logout', 'uses'=>'Agent\IndexController@logout']);
Route::match(['post','get'], '/find',['as'=>'agent.index.find', 'uses'=>'Agent\IndexController@find']);
Route::get('/findOk',['as'=>'agent.index.findOk', 'uses'=>'Agent\IndexController@findOk']);
Route::match(['post','get'], '/findPassword',['as'=>'agent.index.findPassword', 'uses'=>'Agent\IndexController@findPassword']);
Route::match(['post','get'], '/agent/changePassword',['as'=>'agent.user.changePassword', 'uses'=>'Agent\AgentController@changePassword']);
Route::match(['post','get'], '/agent/changeEmail',['as'=>'agent.user.changeEmail', 'uses'=>'Agent\AgentController@changeEmail']);
Route::get('/agent/index', ['as'=>'agent.user.index', 'uses'=>'Agent\AgentController@index']);
Route::get('/agent/account', ['as'=>'agent.user.account', 'uses'=>'Agent\AgentController@account']);


Route::match(['post','get'], '/info',['as'=>'agent.index.info', 'uses'=>'Agent\AgentController@info']);

//简历
Route::get('/resume', ['as'=>'agent.resume.index', 'uses'=>'Agent\ResumeController@index']);
Route::match(['post','get'], '/resume/input/{resume_id?}',['as'=>'agent.resume.input', 'uses'=>'Agent\ResumeController@input']);
Route::post('/resume/delete', ['as'=>'agent.resume.delete', 'uses'=>'Agent\ResumeController@deleted']);
Route::match(['post'], '/validatorItem',['as'=>'agent.resume.validatorItem', 'uses'=>'Agent\ResumeController@validatorItem']);
Route::match(['post'], '/saveStatus',['as'=>'agent.resume.saveStatus', 'uses'=>'Agent\ResumeController@saveStatus']);
Route::match(['post'], '/jobList',['as'=>'agent.resume.jobList', 'uses'=>'Agent\ResumeController@jobList']);
Route::match(['post'], '/sendMail',['as'=>'agent.resume.sendMail', 'uses'=>'Agent\ResumeController@sendMail']);

Route::match(['get'], '/resume/user_info/{user_id?}',['as'=>'agent.resume.user_info', 'uses'=>'Agent\ResumeController@user_info']);