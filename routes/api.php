<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', ['as' => 'index.index', 'uses' => 'Web\IndexController@index']);

#文件上传
Route::post('/upload/{object}', ['as' => 'upload.save', 'uses' => 'Api\UploadController@save']);
Route::post('/deleteFile', ['as' => 'upload.deleteFile', 'uses' => 'Api\UploadController@deleteFile']);

Route::post('/fileupload/{object}', ['as' => 'upload.save', 'uses' => 'Api\UploadController@savefile']);

Route::match(['post','get'], '/upload/delete/{id}', ['as' => 'upload.delete', 'uses' => 'Api\UploadController@delete']);

#短信验证码
Route::post('/verification/send/{type}', ['as' => 'verification.send', 'uses' => 'Api\VerificationController@send']);

//用户登录注册
Route::post('/login', ['as' => 'user.login', 'uses' => 'Api\UserController@login']);
Route::post('/register', ['as' => 'user.register', 'uses' => 'Api\UserController@register']);
Route::post('/logout', ['as' => 'user.logout', 'uses' => 'Api\UserController@logout']);
Route::post('/user/edit', ['as' => 'user.edit', 'uses' => 'Api\UserController@edit']);
//我的
Route::post('/user/center', ['as' => 'user.center', 'uses' => 'Api\UserController@center']);

//员工
Route::post('/waiter/login', ['as' => 'waiter.login', 'uses' => 'Api\WaiterController@login']);
Route::post('/waiter/forgetpwd', ['as' => 'waiter.forgetpwd', 'uses' => 'Api\WaiterController@forgetpwd']);

//店铺相关
Route::post('/shops', ['as' => 'shops.list', 'uses' => 'Api\ShopsController@index']);
Route::post('/shops/view/{shop_id}', ['as' => 'shops.view', 'uses' => 'Api\ShopsController@view']);
Route::post('/shops/goodsList', ['as' => 'shops.goodsList', 'uses' => 'Api\ShopsController@goods']);

//收货地址相关
Route::post('/address/list', ['as' => 'address.list', 'uses' => 'Api\AddressController@index']);
Route::post('/address/created/{id?}', ['as' => 'address.created', 'uses' => 'Api\AddressController@created']);
Route::post('/address/delete/{id}', ['as' => 'address.deleted', 'uses' => 'Api\AddressController@deleted']);


//预约相关接口
Route::post('/booking/list', ['as' => 'api.booking.list', 'uses' => 'Api\BookingController@index']);
Route::post('/booking/created/{id?}', ['as' => 'api.booking.created', 'uses' => 'Api\BookingController@created']);
Route::post('/booking/deleted/{id}', ['as' => 'api.booking.deleted', 'uses' => 'Api\BookingController@deleted']);
Route::post('/booking/confirm', ['as' => 'api.booking.confirm', 'uses' => 'Api\BookingController@confirm']);
Route::post('/booking/eat', ['as' => 'api.booking.eat', 'uses' => 'Api\BookingController@eat']);
Route::post('/booking/mylist', ['as' => 'api.booking.mylist', 'uses' => 'Api\BookingController@mybooking']);

//队列相关api
Route::post('/queues', ['as' => 'api.queues', 'uses' => 'Api\QueuesController@index']);
Route::post('/queues/list', ['as' => 'api.queues.lists', 'uses' => 'Api\QueuesController@lists']);
Route::post('/queues/apply', ['as' => 'api.queues.apply', 'uses' => 'Api\QueuesController@created']);
Route::post('/queues/statistics', ['as' => 'api.queues.statistics', 'uses' => 'Api\QueuesController@statistics']);
Route::post('/queues/cancel', ['as' => 'api.queues.cancel', 'uses' => 'Api\QueuesController@cancel']);
Route::post('/queues/expire', ['as' => 'api.queues.expire', 'uses' => 'Api\QueuesController@expire']);
Route::post('/queues/eat', ['as' => 'api.queues.eat', 'uses' => 'Api\QueuesController@eat']);
Route::post('/queues/jumping', ['as' => 'api.queues.jumping', 'uses' => 'Api\QueuesController@jumping']);
Route::post('/queues/callNumber', ['as' => 'api.queues.callNumber', 'uses' => 'Api\QueuesController@callNumber']);


Route::post('/setting/queueState', ['as' => 'api.setting.queueState', 'uses' => 'Api\SettingController@queueState']);

//订单相关api
Route::post('/orders/created', ['as' => 'api.orders.created', 'uses' => 'Api\OrderController@created']);
Route::post('/orders/view/{id}', ['as' => 'api.orders.view', 'uses' => 'Api\OrderController@view']);
Route::post('/orders/list', ['as' => 'api.orders.list', 'uses' => 'Api\OrderController@index']);
Route::post('/orders/payment/{id}', ['as' => 'api.orders.payment', 'uses' => 'Api\OrderController@payment']);
Route::post('/orders/offlinePay/{id}', ['as' => 'api.orders.offlinePay', 'uses' => 'Api\OrderController@offlinePay']);
Route::post('/orders/return/{id}', ['as' => 'api.orders.refundGoods', 'uses' => 'Api\OrderController@refundGoods']);
Route::post('/orders/serving/{id}', ['as' => 'api.orders.serving', 'uses' => 'Api\OrderController@serving']);
Route::post('/orders/complete', ['as' => 'api.orders.complete', 'uses' => 'Api\OrderController@complete']);
Route::post('/orders/cancel', ['as' => 'api.orders.cancel', 'uses' => 'Api\OrderController@cancel']);
Route::post('/orders/receipt', ['as' => 'api.orders.receipt', 'uses' => 'Api\OrderController@receipt']);
Route::post('/orders/delivery', ['as' => 'api.orders.delivery', 'uses' => 'Api\OrderController@delivery']);

Route::match(['post','get'], '/callback/{paymethod}/orderNotify', ['as' => 'callback.orderNotify', 'uses' => 'Api\CallbackController@orderNotify']);
Route::match(['post','get'], '/callback/{method}/takeoutNotify', ['as' => 'callback.takeout', 'uses' => 'Api\CallbackController@takeoutNotify']);

Route::post('/test/dada', ['as' => 'api.test.data', 'uses' => 'Api\TestController@index']);
