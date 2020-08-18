<?php
namespace App\Services\Dada\Sdk\api;

use App\Services\Dada\Sdk\config\UrlConfig;
/**
 * 发单api
 */
class AddOrderApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::ORDER_ADD_URL, $params);
    }
}
