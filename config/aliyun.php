<?php

/**
 * aliocr
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 14:16:47
 * @copyright   Copyright(C) bravesoft Inc.
 */
return [
    'ocr' => [
        'appCode' => env('ALIYUN_ORC_APP_CODE', NULL)
    ],
    'alidayu' => [
        'accessKeyId' => env('ALIYUN_KEY_ID', 'LTAIGtH2FdVZgm0l'),
        'accessKeySecret' => env('ALIYUN_KEY_SECRET', NULL),
        'templateSign' => env('ALIYUN_DAYU_TEMPLATE_SIGN', '洋富柜儿'),
    ],
    'push' => [
        'accessKeyId' => env('ALIYUN_KEY_ID', 'LTAIGtH2FdVZgm0l'),
        'accessKeySecret' => env('ALIYUN_KEY_SECRET', NULL),
        'androidAppKey' => env('ALIYUN_PUSH_ANDROID_APP_KEY', '25621602'),
        'iosAppKey' => env('ALIYUN_PUSH_IOS_APP_KEY', '25650801'),
    ],
    //ali支付
    'alipay' => [
        'appId' => env('ALIPAY_APP_ID', ''), //app id
        'alipayrsaPublicKey' => env('ALIPAY_PUBLIC_KEY', ''), //public key
        'rsaPrivateKeyFilePath' => storage_path() . "/rsa_private_key.pem",
    ],
];
