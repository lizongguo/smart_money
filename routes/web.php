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



Auth::routes();

//首页
Route::get('/', ['as' => 'web.index.index', 'uses' => 'Web\IndexController@index']);
//企业
Route::get('/company', ['as' => 'web.index.company']);

Route::match(['get'], '/layout.html',['uses'=>'Web\IndexController@layout']);

//agent
Route::match(['post','get'], '/agent', ['as' => 'web.index.agent', 'uses' => 'Web\IndexController@agent']);

//职位详细
Route::get('/jobdetail/{id}', ['as'=>'job.detail', 'uses'=>'Web\IndexController@detail']);

//应募相关
Route::match(['post','get'], '/job/record/{id?}',['as'=>'web.job.record', 'uses'=>'Web\JobRecordController@record']);
Route::match(['get'], '/record/index/{type}',['as'=>'web.record.index', 'uses'=>'Web\JobRecordController@index']);
Route::match(['post'], '/record/memoList',['as'=>'web.record.memoList', 'uses'=>'Web\JobRecordController@memo_list']);
Route::match(['post'], '/record/memoAdd',['as'=>'web.record.memoAdd', 'uses'=>'Web\JobRecordController@memo_add']);
Route::match(['post'], '/record/read',['as'=>'web.record.read', 'uses'=>'Web\JobRecordController@read']);
Route::match(['get', 'post'], '/record/statusList/{id}',['as'=>'web.record.statusList', 'uses'=>'Web\JobRecordController@status_list']);
Route::match(['post'], '/record/statusAdd',['as'=>'web.record.statusAdd', 'uses'=>'Web\JobRecordController@status_add']);

Route::match(['post','get'], '/entry/{id?}',['as'=>'web.resume.add', 'uses'=>'Web\ResumeController@add']);
Route::match(['post'], '/validatorItem',['as'=>'web.resume.validatorItem', 'uses'=>'Web\ResumeController@validatorItem']);

//用户
Route::match(['post','get'], '/login',['as'=>'web.index.login', 'uses'=>'Web\IndexController@login']);
Route::match(['post','get'], '/logout',['as'=>'web.index.logout', 'uses'=>'Web\IndexController@logout']);
Route::match(['post','get'], '/find',['as'=>'web.index.find', 'uses'=>'Web\IndexController@find']);
Route::get('/findOk',['as'=>'web.index.findOk', 'uses'=>'Web\IndexController@findOk']);
Route::match(['post','get'], '/findPassword',['as'=>'web.index.findPassword', 'uses'=>'Web\IndexController@findPassword']);
Route::match(['post','get'], '/user/changePassword',['as'=>'web.user.changePassword', 'uses'=>'Web\UserController@changePassword']);
Route::match(['post','get'], '/user/changeEmail',['as'=>'web.user.changeEmail', 'uses'=>'Web\UserController@changeEmail']);
Route::get('/user/index', ['as'=>'web.user.index', 'uses'=>'Web\UserController@index']);
Route::get('/user/account', ['as'=>'web.user.account', 'uses'=>'Web\UserController@account']);

#详细工作经验
Route::get('/experiences/my',['as'=>'web.experiences.my', 'uses'=>'Web\ExperiencesController@my']);
Route::post('/experiences/store',['as'=>'web.experiences.store', 'uses'=>'Web\ExperiencesController@store']);
Route::get('/experiences/uploadVideo',['as'=>'web.experiences.uploadVideo', 'uses'=>'Web\ExperiencesController@uploadVideo']);
Route::post('/experiences/storeVideo',['as'=>'web.experiences.storeVideo', 'uses'=>'Web\ExperiencesController@storeVideo']);

//下载pdf文档
Route::match(['post', 'get'], '/experiences/download',['as'=>'web.experiences.download', 'uses'=>'Web\ExperiencesController@download']);

//收藏
Route::match(['get'], '/favorite/index',['as'=>'web.favorite.index', 'uses'=>'Web\FavoriteController@index']);
Route::match(['post'], '/favorite/favorite',['as'=>'web.favorite.favorite', 'uses'=>'Web\FavoriteController@favorite']);


//sendmail
Route::match(['post'], '/send_mail/global',['as'=>'web.send_mail.global', 'uses'=>'Web\ResumeController@mailGlobal']);
Route::match(['post'], '/send_mail/about',['as'=>'web.send_mail.about', 'uses'=>'Web\ResumeController@mailAbout']);
Route::match(['post'], '/send_mail/aboutContact',['as'=>'web.send_mail.aboutContact', 'uses'=>'Web\ResumeController@mailAboutContact']);


//基金项目一览
Route::match(['post','get'], '/index/project/{id?}',['as'=>'web.index.project', 'uses'=>'Web\IndexController@project']);
//项目详情
Route::match(['get'], '/index/projectDetail/{id?}/{found_id?}',['as'=>'web.index.projectDetail', 'uses'=>'Web\IndexController@projectDetail']);
//项目风险
Route::match(['get'], '/index/projectRisk/{id?}/{found_id?}',['as'=>'web.index.projectRisk', 'uses'=>'Web\IndexController@projectRisk']);

//我的投资
Route::match(['post','get'], '/index/found',['as'=>'web.index.found', 'uses'=>'Web\IndexController@found']);
//所以基金一览
Route::match(['get'], '/index/foundDetail',['as'=>'web.index.foundDetail', 'uses'=>'Web\IndexController@foundDetail']);

//单支基金详情
Route::match(['get'], '/index/foundDetailAbout/{id?}',['as'=>'web.index.foundDetailAbout', 'uses'=>'Web\IndexController@foundDetailAbout']);

//基金财务报表
Route::match(['get'], '/index/financial/{id?}',['as'=>'web.index.financial', 'uses'=>'Web\IndexController@financial']);
//审计报告
Route::match(['get'], '/index/audit/{id?}',['as'=>'web.index.audit', 'uses'=>'Web\IndexController@audit']);
//出资明细
Route::match(['get'], '/index/investment/{id?}',['as'=>'web.index.investment', 'uses'=>'Web\IndexController@investment']);
//风险提示
Route::match(['get'], '/index/risk/{id?}',['as'=>'web.index.risk', 'uses'=>'Web\IndexController@risk']);
//基金关系图
Route::match(['get'], '/index/diagram/{id?}',['as'=>'web.index.diagram', 'uses'=>'Web\IndexController@diagram']);
//基金关系图
Route::match(['get'], '/index/other/{id?}',['as'=>'web.index.other', 'uses'=>'Web\IndexController@other']);

//文件详细
Route::match(['get'], '/index/filedetail/{id?}/{type?}',['as'=>'web.index.filedetail', 'uses'=>'Web\IndexController@filedetail']);
//底部内容
Route::match(['get','post'], '/index/content/{type}',['as'=>'web.index.content', 'uses'=>'Web\IndexController@content']);
//工具页面
Route::match(['get','post'], '/index/tool',['as'=>'web.index.tool', 'uses'=>'Web\IndexController@tool']);

Route::match(['get'], '/index/contentDetail/{id}',['as'=>'web.index.contentDetail', 'uses'=>'Web\IndexController@contentDetail']);