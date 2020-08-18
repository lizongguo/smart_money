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
use App\Models\GoodsCategory;
use App\Models\Activity;

class ActivityController extends BaseController
{
    public function __construct(Request $request, Activity $model) {
        parent::__construct();
        $this->model = $model;
        
        //获取全部的店铺
        view()->share('shops', Shops::select('id', 'shop_name')->where('deleted', 0)->get());
        //获取全部的分类
        view()->share('categories', GoodsCategory::select('id', 'name')->where('deleted', 0)->get());
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['activity_name'])) {
            $sh['activity_name'] = ['conn' => 'lk', 'value' => $data['activity_name']];
        }
        
        if (!empty($sh['start_date']) && !empty($sh['end_date'])) {
            $sh['start_date'] = ['conn' => '<=', 'value' => $sh['end_date']];
            $sh['end_date'] = ['conn' => '>=', 'value' => $sh['start_date']];
            unset($sh['start_date']);
            unset($sh['end_date']);
        } elseif (!empty($sh['start_date'])) {
            $sh['end_date'] = ['conn' => '>=', 'value' => $sh['start_date']];
            unset($sh['start_date']);
        } elseif (!empty($sh['end_date'])) {
            $sh['start_date'] = ['conn' => '<=', 'value' => $sh['end_date']];
            unset($sh['end_date']);
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
            'activity_name' => 'required',
            'time' => 'regex:/[\d]{4}\-[\d]{2}\-[\d]{2} ~ [\d]{4}\-[\d]{2}\-[\d]{2}/',
        ];
        
        if (!isset($data['type']) || $data['type'] == 1) {
            $valid['full_amount'] = 'regex:/^[\d]+(.[\d]{1,2})?$/';
            $valid['minus_amount'] = 'regex:/^[\d]+(.[\d]{1,2})?$/';
        }else{
            $valid['discount'] = 'regex:/^[\d]+(.[\d]{1,2})?$/';
        }
        
        $tips = [
            'activity_name.required' => '标题为必填项',
            'time.regex' => '活动周期格式错误',
            'full_amount.regex' => '金额格式输入有误',
            'minus_amount.regex' => '金额格式输入有误',
            'discount.regex' => '折扣格式输入有误',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function input(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->getOne($id)) {
            $data = $item;
            $data->id = $id;
        }
        if ($request->isMethod('post')) {
            $data = $request->input('data');
            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorItem') && $this->validatorItem($data, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }
            
            //处理日期
            $times = explode(' ~ ', $data['time']); 
            $data['start_date'] = $times['0'];
            $data['end_date'] = $times['1'];
            
            if ($data['type'] == 1) {
                $data['discount'] = 0;
            } else {
                $data['full_amount'] = 0;
                $data['minus_amount'] = 0;
            }
            
            
            $result = $this->model->saveItem($data);
            //save success
            if($result === false) {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存に失敗しました。',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'msg' => '保存に成功しました。'
                ]);
            }
        }
        return view('admin.' . $this->viewName . '.input', ['data' => $data]);
    }
}
