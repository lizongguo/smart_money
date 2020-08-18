<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Popular;

class PopularController extends BaseController
{
    public function __construct(Request $request, Popular $model) {
        parent::__construct();
        $this->model = $model;
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['title'])) {
            $sh['title'] = ['conn' => 'lk', 'value' => $data['title']];
        }
        return $sh;
    }
}
