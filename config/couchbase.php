<?php

/**
 * couchbase
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-3-28 10:28:06
 * @copyright   Copyright(C) bravesoft Inc.
 */

return [
    'couchbase_host' => env('COUCHBASE_HOST', '10.200.8.97'),
    'couchbase_port' => env('COUCHBASE_PORT', '8091'),
    'couchbase_auth_flag' => env('COUCHBASE_AUTH_FLAG', FALSE),
    'couchbase_auth_name' => env('COUCHBASE_AUTH_NAME', 'Administrator'),
    'couchbase_auth_pw' => env('COUCHBASE_AUTH_PW', 'couchbase'),
    'couchbase_bucket' => env('COUCHBASE_BUCKET', 'mobilekokuho')
];

