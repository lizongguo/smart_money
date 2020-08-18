<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ads;
use Illuminate\Http\Request;

class AdsController extends BaseController
{
    public function __construct(Ads $model) {
        $this->model = $model;
        view()->share('ad_type', config('code.ad_type'));
        parent::__construct();
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['name'])) {
            $sh['name'] = ['conn' => 'llk', 'value' => $data['name']];
        }
        return $sh;
    }
}
