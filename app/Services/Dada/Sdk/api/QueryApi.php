<?php
namespace App\Services\Dada\Sdk\api;

use App\Services\Dada\Sdk\config\UrlConfig;
/**
 * 查询api
 */
class QueryApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::QUERY_URL, $params);
    }
}
