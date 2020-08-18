<?php
namespace App\Services\Dada\Sdk\api;

use App\Services\Dada\Sdk\config\UrlConfig;
/**
 * 发单api
 */
class CancelApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::CANCEL_URL, $params);
    }
}
