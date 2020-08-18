<?php

/**
 * solr config
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-17 13:14:39
 * @copyright   Copyright(C) bravesoft Inc.
 */
return [
    'endpoint' => [
        'localhost' => [
            'host' => env('SOLR_HOST', 'localhost'),
            'port' => env('SOLR_PORT', 8983),
            'path' => env('SOLR_PATH', '/solr'),
            'core' => 'hourei_app_live',
            'timeout' => env('SOLR_TIMEOUT', '30'),
        ]
    ]
];

