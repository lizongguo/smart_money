<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Address;
use Validator;

class AddressController extends BaseController
{
    protected $model = null;

    public function __construct(Request $request, Address $model)
    {
        $this->model = $model;
        parent::__construct($request);
    }
    
    /**
     * 获取用户收货地址列表
     * @param Request $request
     */
    public function index(Request $request) {
        $address = $this->model->getList(['user_id' => $this->user['id']], TRUE, 0, [
            'id', 'user_id', 'accept_name', 'gender', 'telphone', 'address', 'full_address', 'long', 'lat', 'is_default'
        ]);

        $this->back['data'] = $address;
        
        return $this->back;
    }
    
    /**
     * 获取用户收货地址列表
     * @param Request $request
     */
    public function created(Request $request, $id = 0) {
        $data = $request->all();
        $item = $this->model->getOne($id);
        if (!!$item) {
            if ($item['user_id'] != $this->user['id']) {
                $this->back['status'] = '400';
                $this->back['msg'] = '拒绝访问。';
                return $this->dataToJson($this->back);
            }
            $data['id'] = $item['id'];
        }
        
        $data['user_id'] = $this->user['id'];
        
        $validator = Validator::make($data, [
            'accept_name' => 'required',
            'gender' => 'regex:/^[12]$/',
            'telphone' => 'regex:/^1[\d]{10}$/',
            'address' => 'required',
            'long' => 'numeric',
            'lat' => 'numeric',
            'is_default' => 'in:0,1'
        ], [
            'accept_name.required' => '联系人为必填项',
            'gender.regex' => '性别为必填项',
            'telphone.regex' => '联系电话格式有误',
            'address.required' => '联系地址不能为空',
            'long.numeric' => '经度格式输入错误',
            'lat.numeric' => '纬度格式输入错误',
            'is_default' => '默认地址参数错误',
        ]);
        
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $rs = $this->model->saveAddress($data);
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误。';
            return $this->dataToJson($this->back);
        }
        return $this->back;
    }
    
    /**
     * 删除我的收货地址
     * @param type $id 地址id
     * @return type
     */
    public function deleted($id) {
        $item = $this->model->getOne(intval($id));
        
        if (!$item || $item->user_id != $this->user['id']) {
            $this->back['status'] = '400';
            $this->back['msg'] = '拒绝访问。';
            return $this->dataToJson($this->back);
        }
        
        $rs = $item->delete();
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '地址删除失败。';
            return $this->dataToJson($this->back);
        }
        return $this->back;
    }
    
    
}