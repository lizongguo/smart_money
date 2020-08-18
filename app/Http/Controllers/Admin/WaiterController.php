<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Waiter;
use App\Models\Shops;

class WaiterController extends BaseController
{
    public function __construct(Request $request, Waiter $model) {
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
        $sh['role'] = 1; //用户
        if (!empty($data['username'])) {
            $sh['username'] = ['conn' => 'lk', 'value' => $data['username']];
        }
        
        if (!empty($data['phone'])) {
            $sh['phone'] = ['conn' => 'lk', 'value' => $data['phone']];
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
            'username' => 'required',
            'avatar' => 'required',
            'phone' => "required|regex:#^1[\d]{10}$#|unique:users,phone,{$data['id']},id,role,1",
            'shop_id' => 'required',
            'remarks' => 'max:100',
        ];
        $tips = [
            'name.required' => '用户名为必填项',
            'avatar.required' => '用户头像不能为空',
            'email.unique' => '邮箱已经注册，请更换邮箱',
            'phone.unique' => '手机号已经注册，请更换手机号',
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式输入错误',
            'shop_id.required' => '店铺不能为空',
            'remark.max' => '备注长度不能超过100个字',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
}
