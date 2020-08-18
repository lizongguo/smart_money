<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends BaseController
{
    public function __construct(Category $model) {
        $this->model = $model;
        parent::__construct();
    }
    
    /**
     * 列表数据json
     * @param Request $request
     * @return json_encode
     */
    public function items(Request $request) {
        $data = $this->model->select('id', 'name', 'parent_id', 'order', 'state')
            ->where('deleted', 0)
            ->get()->toArray();
        return response()->json($data);
    }
    
    public function input(Request $request, $id = 0) {
        //获取设定数据
        view()->share('parents', $this->model->selectOptions(function($obj){
            return $obj->select('id', 'name as title', 'parent_id', 'order')->where('deleted', 0);
        }), 'ROOT');
        
        return parent::input($request, $id);
    }
    
    
    
}
