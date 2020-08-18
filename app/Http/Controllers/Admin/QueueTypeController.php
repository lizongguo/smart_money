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
use App\Models\QueueType;

class QueueTypeController extends BaseController
{
    public function __construct(Request $request, QueueType $model) {
        parent::__construct();
        $this->model = $model;
        //获取全部的店铺
        view()->share('shops', Shops::select('id', 'shop_name')->where('deleted', 0)->get());
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
            $sh['name'] = ['conn' => 'lk', 'value' => $data['name']];
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
            'name' => 'required',
            'shop_id' => 'required',
            'prefix' => 'regex:/[a-z]{1}/i|unique:queue_type,prefix,' . $data['id'] . ',id,shop_id,' . $data['shop_id'],
        ];
        $tips = [
            'name.required' => '名称为必填项',
            'shop_id.required' => '店铺为必选项',
            'prefix.regex' => '队列前缀格式错误',
            'prefix.unique' => '队列前缀已存在，请修改',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
}
