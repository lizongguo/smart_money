<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;

class NewsController extends BaseController
{
    public function __construct(News $model) {
        $this->model = $model;
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
        if (!empty($data['title'])) {
            $sh['title'] = ['conn' => 'llk', 'value' => $data['title']];
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
            'category_id' => 'required',
            'title' => 'required',
            'thumb' => 'required',
            'content' => 'required',
        ];
        $tips = [
            'name.required' => '标题不能为空',
            'category_id.required' => '分类不能为空',
            'thumb.required' => '封面图不能为空',
            'hits.required' => '点击量不能为空',
            'content.required' => '内容不能为空',
            'slug.regex' => '角色标示格式输入错误',
        ];
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
    
    public function input(Request $request, $id = 0) {
        //获取设定数据
        view()->share('categories', Category::selectOptions(function($obj){
            return $obj->select('id', 'name as title', 'parent_id', 'order')->where('deleted', 0);
        }, '请选择'));
        
        return parent::input($request, $id);
    }
    
    
}
