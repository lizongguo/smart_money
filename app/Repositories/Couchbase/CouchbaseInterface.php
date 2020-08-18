<?php

/**
 * CouchbaseInterface
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-3-27 17:02:06
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Repositories\Couchbase;

interface CouchbaseInterface {
    
    public function init();
    public function getDataList(int $page = 1, int $limit = 10);
    public function getDataByDocid(string $docid);
    
}
