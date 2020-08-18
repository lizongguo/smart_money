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
 * 点餐用户
 */
Route::get('/users', ['as'=>'users.index', 'uses'=>'Admin\UsersController@index']);
Route::get('/users/items', ['as'=>'users.items', 'uses'=>'Admin\UsersController@items']);
Route::match(['post','get'], '/users/input/{id?}',['as'=>'users.input', 'uses'=>'Admin\UsersController@input']);
Route::get('/users/detail/{id}', ['as'=>'users.detail', 'uses'=>'Admin\UsersController@detail']);
Route::get('/users/delete/{id}', ['as'=>'users.delete', 'uses'=>'Admin\UsersController@deleted']);

/**
 * 投资人信息
 */
Route::get('/found_user', ['as'=>'users.founduser', 'uses'=>'Admin\UsersController@founduser']);
Route::get('/users/founditems', ['as'=>'users.founditems', 'uses'=>'Admin\UsersController@founditems']);


/**
 * 基金管理
 */
Route::get('/found', ['as'=>'found.index', 'uses'=>'Admin\FoundController@index']);
Route::get('/found/items', ['as'=>'found.items', 'uses'=>'Admin\FoundController@items']);
Route::get('/found/project', ['as'=>'found.project', 'uses'=>'Admin\FoundController@project']);
Route::match(['post','get'], '/found/input/{id?}',['as'=>'found.input', 'uses'=>'Admin\FoundController@input']);

//财务报表
Route::match(['post','get'], '/found/financial/{id?}',['as'=>'found.financial', 'uses'=>'Admin\FoundController@financial']);
Route::get('/financial/items', ['as'=>'financial.items', 'uses'=>'Admin\FoundFinancialController@item']);
Route::get('/financial/delete/{id}', ['as'=>'financial.delete', 'uses'=>'Admin\FoundFinancialController@deleted']);
Route::match(['post','get'], '/financial/input/{id?}',['as'=>'financial.input', 'uses'=>'Admin\FoundFinancialController@input']);


//审计报告
Route::match(['post','get'], '/found/audit/{id?}',['as'=>'found.audit', 'uses'=>'Admin\FoundAuditController@index']);
Route::get('/audit/items', ['as'=>'audit.items', 'uses'=>'Admin\FoundAuditController@item']);
Route::get('/audit/delete/{id}', ['as'=>'audit.delete', 'uses'=>'Admin\FoundAuditController@deleted']);
Route::match(['post','get'], '/audit/input/{id?}',['as'=>'audit.input', 'uses'=>'Admin\FoundAuditController@input']);

//风险提示
Route::match(['post','get'], '/found/risk/{id?}',['as'=>'found.risk', 'uses'=>'Admin\FoundRiskController@index']);
Route::get('/risk/items', ['as'=>'risk.items', 'uses'=>'Admin\FoundRiskController@item']);
Route::get('/risk/delete/{id}', ['as'=>'risk.delete', 'uses'=>'Admin\FoundRiskController@deleted']);
Route::match(['post','get'], '/risk/input/{id?}',['as'=>'risk.input', 'uses'=>'Admin\FoundRiskController@input']);

//其他
Route::match(['post','get'], '/found/other/{id?}',['as'=>'found.other', 'uses'=>'Admin\FoundOtherController@index']);
Route::get('/other/items', ['as'=>'other.items', 'uses'=>'Admin\FoundOtherController@item']);
Route::get('/other/delete/{id}', ['as'=>'other.delete', 'uses'=>'Admin\FoundOtherController@deleted']);
Route::match(['post','get'], '/other/input/{id?}',['as'=>'other.input', 'uses'=>'Admin\FoundOtherController@input']);

//出资明细
Route::match(['post','get'], '/found/capital/{id?}',['as'=>'found.capital', 'uses'=>'Admin\FoundCapitalController@index']);
Route::get('/capital/items', ['as'=>'capital.items', 'uses'=>'Admin\FoundCapitalController@item']);
Route::get('/capital/delete/{id}', ['as'=>'capital.delete', 'uses'=>'Admin\FoundCapitalController@deleted']);
Route::match(['post','get'], '/capital/input/{id?}',['as'=>'capital.input', 'uses'=>'Admin\FoundCapitalController@input']);


//项目管理
Route::get('/project', ['as'=>'project.index', 'uses'=>'Admin\ProjectController@index']);
Route::get('/project/items', ['as'=>'project.items', 'uses'=>'Admin\ProjectController@items']);
Route::match(['post','get'], '/project/input/{id?}',['as'=>'project.input', 'uses'=>'Admin\ProjectController@input']);

//项目风险
Route::match(['post','get'], '/project/risk/{id?}',['as'=>'project.risk', 'uses'=>'Admin\ProjectRiskController@index']);
Route::get('/prisk/items', ['as'=>'prisk.items', 'uses'=>'Admin\ProjectRiskController@item']);
Route::get('/prisk/delete/{id}', ['as'=>'prisk.delete', 'uses'=>'Admin\ProjectRiskController@deleted']);
Route::match(['post','get'], '/prisk/input/{id?}',['as'=>'prisk.input', 'uses'=>'Admin\ProjectRiskController@input']);

//内容管理
Route::get('/content', ['as'=>'content.index', 'uses'=>'Admin\ContentController@index']);
Route::get('/content/items', ['as'=>'content.items', 'uses'=>'Admin\ContentController@items']);
Route::match(['post','get'], '/content/input/{id?}',['as'=>'content.input', 'uses'=>'Admin\ContentController@input']);
Route::get('/content/delete/{id}', ['as'=>'content.delete', 'uses'=>'Admin\ContentController@deleted']);


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


/**
 * 文章分类管理
 */
Route::get('/category', ['as'=>'category.index', 'uses'=>'Admin\CategoryController@index']);
Route::get('/category/items', ['as'=>'category.items', 'uses'=>'Admin\CategoryController@items']);
Route::match(['post','get'], '/category/input/{id?}',['as'=>'category.input', 'uses'=>'Admin\CategoryController@input']);
Route::get('/category/delete/{id}', ['as'=>'category.delete', 'uses'=>'Admin\CategoryController@deleted']);

//文章管理
Route::get('/news', ['as'=>'news.index', 'uses'=>'Admin\NewsController@index']);
Route::get('/news/items', ['as'=>'news.items', 'uses'=>'Admin\NewsController@items']);
Route::match(['post','get'], '/news/input/{id?}',['as'=>'news.input', 'uses'=>'Admin\NewsController@input']);
Route::get('/news/delete/{id}', ['as'=>'news.delete', 'uses'=>'Admin\NewsController@deleted']);

//广告横条
Route::get('/ads', ['as'=>'ads.index', 'uses'=>'Admin\AdsController@index']);
Route::get('/ads/items', ['as'=>'ads.items', 'uses'=>'Admin\AdsController@items']);
Route::match(['post','get'], '/ads/input/{id?}',['as'=>'ads.input', 'uses'=>'Admin\AdsController@input']);
Route::get('/ads/delete/{id}', ['as'=>'ads.delete', 'uses'=>'Admin\AdsController@deleted']);

//#导入地区表
//Route::get('/areas/insert', ['as'=>'areas.insert', 'uses'=>'Admin\AreaController@index']);
//Route::get('/user/state/{id}', ['as'=>'print.state', 'uses'=>'Admin\PrintController@state']);

//#履历
Route::get('/resume', ['as'=>'resume.index', 'uses'=>'Admin\ResumeController@index']);
Route::get('/resume/items', ['as'=>'resume.items', 'uses'=>'Admin\ResumeController@items']);
Route::match(['post','get'], '/resume/input/{job_id?}',['as'=>'resume.input', 'uses'=>'Admin\ResumeController@input']);
Route::get('/resume/delete/{id}', ['as'=>'resume.delete', 'uses'=>'Admin\ResumeController@deleted']);
Route::post('/resume/save_pdf/{resume}', ['as'=>'resume.save_pdf', 'uses'=>'Admin\ResumeController@savePdf']);

/**
 * 履歴書・職務経歴書 管理
 */
Route::get('/experience', ['as'=>'experience.index', 'uses'=>'Admin\ExperienceController@index']);
Route::get('/experience/items', ['as'=>'experience.items', 'uses'=>'Admin\ExperienceController@items']);
Route::match(['post','get'], '/experience/input/{id?}',['as'=>'experience.input', 'uses'=>'Admin\ExperienceController@input']);
Route::get('/experience/detail/{id}', ['as'=>'experience.detail', 'uses'=>'Admin\ExperienceController@detail']);
Route::get('/experience/delete/{id}', ['as'=>'experience.delete', 'uses'=>'Admin\ExperienceController@deleted']);


Route::get('/job', ['as'=>'job.index', 'uses'=>'Admin\JobController@index']);
Route::get('/job/items', ['as'=>'job.items', 'uses'=>'Admin\JobController@items']);
Route::match(['post','get'], '/job/input/{job_id?}',['as'=>'job.input', 'uses'=>'Admin\JobController@input']);
Route::get('/job/delete/{id}', ['as'=>'job.delete', 'uses'=>'Admin\JobController@deleted']);
//Route::get('/job/state/{id}', ['as'=>'goods.state', 'uses'=>'Admin\GoodsController@state']);


Route::get('/popular', ['as'=>'popular.index', 'uses'=>'Admin\PopularController@index']);
Route::get('/popular/items', ['as'=>'popular.items', 'uses'=>'Admin\PopularController@items']);
Route::match(['post','get'], '/popular/input/{job_id?}',['as'=>'popular.input', 'uses'=>'Admin\PopularController@input']);
Route::get('/popular/delete/{id}', ['as'=>'popular.delete', 'uses'=>'Admin\PopularController@deleted']);
//Route::get('/job/state/{id}', ['as'=>'goods.state', 'uses'=>'Admin\GoodsController@state']);


/**
 * 企業管理
 */
Route::get('/company', ['as'=>'company.index', 'uses'=>'Admin\CompanyController@index']);
Route::get('/company/items', ['as'=>'company.items', 'uses'=>'Admin\CompanyController@items']);
Route::match(['post','get'], '/company/input/{id?}',['as'=>'company.input', 'uses'=>'Admin\CompanyController@input']);
Route::get('/company/delete/{id}', ['as'=>'company.delete', 'uses'=>'Admin\CompanyController@deleted']);

/**
 * 企業管理
 */
Route::get('/agent', ['as'=>'agent.index', 'uses'=>'Admin\AgentController@index']);
Route::get('/agent/items', ['as'=>'agent.items', 'uses'=>'Admin\AgentController@items']);
Route::match(['post','get'], '/agent/input/{id?}',['as'=>'agent.input', 'uses'=>'Admin\AgentController@input']);
Route::get('/agent/delete/{id}', ['as'=>'agent.delete', 'uses'=>'Admin\AgentController@deleted']);

/**
 * 就職塾
 */
Route::get('/contact', ['as'=>'contact.index', 'uses'=>'Admin\ContactController@index']);
Route::get('/contact/items', ['as'=>'contact.items', 'uses'=>'Admin\ContactController@items']);
Route::match(['post','get'], '/contact/input/{id?}',['as'=>'contact.input', 'uses'=>'Admin\ContactController@input']);
Route::get('/contact/delete/{id}', ['as'=>'contact.delete', 'uses'=>'Admin\ContactController@deleted']);


/**
 * RA企業管理（RA用）
 */
Route::get('/enterprise_employment_records', ['as'=>'enterprise_employment_records.index', 'uses'=>'Admin\EnterpriseEmploymentRecordsController@index']);
Route::get('/enterprise_employment_records/items', ['as'=>'enterprise_employment_records.items', 'uses'=>'Admin\EnterpriseEmploymentRecordsController@items']);
Route::match(['post', 'get'], '/enterprise_employment_records/input/{id?}',['as'=>'enterprise_employment_records.input', 'uses'=>'Admin\EnterpriseEmploymentRecordsController@input']);
Route::match(['post', 'get'], '/enterprise_employment_records/state/{id}', ['as'=>'enterprise_employment_records.state', 'uses'=>'Admin\EnterpriseEmploymentRecordsController@state']);
Route::get('/enterprise_employment_records/delete/{enterpriseemploymentrecord}', ['as'=>'enterprise_employment_records.delete', 'uses'=>'Admin\EnterpriseEmploymentRecordsController@destroy']);


/**
 * 求職者管理（CA用）
 */

Route::match(['post','get'], '/employment_records/add_company/{id}', ['as'=>'employment_records.add_company', 'uses'=>'Admin\EmploymentRecordsController@addCompany']);

Route::match(['post','get'], '/employment_records/add_job/{id}', ['as'=>'employment_records.add_job', 'uses'=>'Admin\EmploymentRecordsController@addJob']);
Route::get('/employment_records', ['as'=>'employment_records.index', 'uses'=>'Admin\EmploymentRecordsController@index']);
Route::get('/employment_records/items', ['as'=>'employment_records.items', 'uses'=>'Admin\EmploymentRecordsController@items']);
Route::match(['post', 'get'], '/employment_records/input/{id?}',['as'=>'employment_records.input', 'uses'=>'Admin\EmploymentRecordsController@input']);
Route::get('/employment_records/delete/{employmentrecord}', ['as'=>'employment_records.delete', 'uses'=>'Admin\EmploymentRecordsController@destroy']);

Route::get('/employment_records/delCompany/{company_id}', ['as'=>'employment_records.delCompany', 'uses'=>'Admin\EmploymentRecordsController@delCompany']);

Route::get('/employment_records/delJob/{id}', ['as'=>'employment_records.delJob', 'uses'=>'Admin\EmploymentRecordsController@delJob']);



Route::get('/employment_record_comments/index', ['as'=>'employment_record_comments.index', 'uses'=>'Admin\EmploymentRecordCommentsController@index']);
Route::get('/employment_record_comments/items', ['as'=>'employment_record_comments.items', 'uses'=>'Admin\EmploymentRecordCommentsController@items']);
Route::get('/employment_record_comments/{id?}',['as'=>'employment_record_comments.input', 'uses'=>'Admin\EmploymentRecordCommentsController@input']);
Route::post('/employment_record_comments/{employmentrecordcomment}',['as'=>'employment_record_comments.update', 'uses'=>'Admin\EmploymentRecordCommentsController@update']);
Route::post('/employment_record_comments',['as'=>'employment_record_comments.store', 'uses'=>'Admin\EmploymentRecordCommentsController@store']);
Route::get('/employment_record_comments/delete/{employmentrecordcomment}', ['as'=>'employment_record_comments.delete', 'uses'=>'Admin\EmploymentRecordCommentsController@destroy']);


Route::get('/enterprise_employment_memos/index', ['as'=>'enterprise_employment_memos.index', 'uses'=>'Admin\EnterpriseEmploymentMemosController@index']);
Route::get('/enterprise_employment_memos/items', ['as'=>'enterprise_employment_memos.items', 'uses'=>'Admin\EnterpriseEmploymentMemosController@items']);
Route::get('/enterprise_employment_memos/{id?}',['as'=>'enterprise_employment_memos.input', 'uses'=>'Admin\EnterpriseEmploymentMemosController@input']);
Route::post('/enterprise_employment_memos/{enterpriseemploymentmemo}',['as'=>'enterprise_employment_memos.update', 'uses'=>'Admin\EnterpriseEmploymentMemosController@update']);
Route::post('/enterprise_employment_memos',['as'=>'enterprise_employment_memos.store', 'uses'=>'Admin\EnterpriseEmploymentMemosController@store']);
Route::get('/enterprise_employment_memos/delete/{employmentrecordcomment}', ['as'=>'enterprise_employment_memos.delete', 'uses'=>'Admin\EnterpriseEmploymentMemosController@destroy']);


    //自动新增路由处，勿删改本行注释


