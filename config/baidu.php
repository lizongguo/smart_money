<?php

/**
 * baidu sms
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2019-1-21 14:16:47
 * @copyright   Copyright(C) kbftech Inc.
 */
return [
    'sms' => [
        'accessKeyId' => env('BAIDU_ACCESS_KEY', '9e85f11589b74935aecafc51314c4911'),
        'accessKeySecret' => env('BAIDU_ACCESS_KEY_SECRET', NULL),
        'templateSign' => env('BAIDU_TEMPLATE_SIGN', '8aSeIbiV-a5Sz-rpzt'),
    ],
];
