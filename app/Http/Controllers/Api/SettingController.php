<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Validator;

class SettingController extends BaseController
{
    protected $model = null;

    public function __construct(Request $request, Setting $model)
    {
        $this->model = $model;
        parent::__construct($request);
    }
    
    /**
     * 获取队列情报
     * @param Request $request
     */
    public function queueState(Request $request)
    {
        $data = $request->all();
        
        if ($this->user['role'] != 1 || $this->user['queue_permission'] != 1) {
            $this->back['status'] = '400';
            $this->back['msg'] = "您没有设置该状态的权限。";
            return $this->dataToJson($this->back);
        }
        
        $validator = Validator::make($data, [
            'state' => 'regex:/^[01]$/',
        ], [
            'state.regex' => '队列状态格式有误',
        ]);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        //修改设置
        $rs = $this->model
            ->where('shop_id', $this->user['shop_id'])
            ->where('name', 'queue_state')
            ->update(['value' => intval($data['state'])]);
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = "状态修改失败。";
            return $this->dataToJson($this->back); 
        }
        
        return $this->back;
    }
    
}