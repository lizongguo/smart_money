<?php
namespace App\Services\Dada\Sdk\api;

use App\Services\Dada\Sdk\config\UrlConfig;
/**
 * 发单api
 */

class CityCodeApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::CITY_ORDER_URL, $params);
    }
}
