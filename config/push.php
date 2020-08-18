<?php

/**
 * push
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-26 11:30:33
 * @copyright   Copyright(C) bravesoft Inc.
 */
return [
    'push_env' => env('PUSH_ENV', 0), //1：SANDBOX  0：PRODUCTION,
    'push_cert_path' =>  app()->basePath() . DIRECTORY_SEPARATOR . 'cert', //certificate file path
    'provider_cert_pass' =>  env('PUSH_CERT_PASS', NULL),
    'push_write_interval' => env('PUSH_WRITE_INTERVAL', NULL), //ms
];

