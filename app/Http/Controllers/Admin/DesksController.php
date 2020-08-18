<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Shops;
use App\Models\Desks;
use App\Models\QueueType;

class DesksController extends BaseController
{
    public function __construct(Request $request, Desks $model) {
        parent::__construct();
        $this->model = $model;
        //获取全部的店铺
        view()->share('shops', Shops::select('id', 'shop_name')->where('deleted', 0)->get());
        //获取全部的分类
        view()->share('types', QueueType::select('id', 'name', 'shop_id')->get());
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        
        if (!empty($data['alias'])) {
            $sh['alias'] = ['conn' => 'lk', 'value' => $data['alias']];
        }
        
        if (!isset($data['state']) || strlen($data['state']) < 1) {
            unset($data['state']);
        }
        return $sh;
    }
    
    
    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'alias' => 'required',
            'shop_id' => 'required',
            'number.required' => 'number',
        ];
        $tips = [
            'alias.required' => '桌位名为必填项',
            'shop_id.required' => '店铺为必选项',
            'number.number' => '座位数格式输入错误',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
}
