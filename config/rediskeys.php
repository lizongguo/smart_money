<?php

/**
 * rediskeys
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-6 10:54:34
 * @copyright   Copyright(C) bravesoft Inc.
 */
return [
    'user_access_hash' => 'user_access_%s:h',  // access => user arr
    'verification_register_hash' => 'verification_reg:h', // 注册验证码  phone => {code:code, time: time()}
    'verification_forgetpwd_hash' => 'verification_forget:h', // 忘记密码验证码  phone => {code:code, time: time()}
    'verification_invite_hash' => 'verification_invite:h', // 业务员邀请用户验证码  phone => {code:code, time: time()}
    'verification_change_hash' => 'verification_change:h', // 用户修改手机号 验证码  phone => {code:code, time: time()}
    'verification_email_hash' => 'valid_email:h', //业务员邀请用户验证码  phone => {code:code, time: time()}
    'app_name_hash' => 'app_%s:h', //存储app相关情报的信息
    'areas' => 'areas_key', //存储省市数据,
    'order_push_queue' => 'order_id_push:q', //存储订单推送的id队列
    'order_meal_no' => 'mealno_%s:k', //获取店铺当天取餐号
    'shop_apply_list' => 'shop_apply_%s:h', //存储服务员申请list user_id => {user_id: xxx, time: time()}
    'queue_type_no' => 'queue_type_no_%s:k', //获取店铺当天队列的队列号
    
];
