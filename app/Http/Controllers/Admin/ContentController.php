<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;
use DB;


class ContentController extends BaseController
{
    public function __construct(Request $request, Content $model) {
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
        if (!empty($data['goods_name'])) {
            $sh['goods_name'] = ['conn' => 'lk', 'value' => $data['goods_name']];
        }
        return $sh;
    }

    function items(Request $request) {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $offset = ($page-1) * $limit;
        $sh = $request->input('sh', []);
        $obj = $this->model->select('*', 'title as name');
        $obj->where('deleted', 0);
        if ($sh['title']) {
            $obj->where('title', 'like', '%'.$sh['title'].'%');
        }
        $count = count($obj->get()->toArray());
        $data = $obj->limit($limit)->offset($offset)->get()->toArray();
        $project_state = config('code.buttom_type');
        foreach ($data as $k => &$v) {
            $v['type_str'] = $project_state[$v['type']];
        }
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $data,
        ]);
    }

    protected function validatorItem($data, &$msg) {
        $valid = [
            'content' => 'required',
        ];
        $tips = [
            'content.numeric' => '内容不能为空',
        ];
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }

    //编辑项目信息
    function input(Request $request, $id = 0) {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->getFoundById($id)) {
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
            $result = $this->model->saveData($data);
            if($result === false) {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存失败',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'msg' => '保存成功'
                ]);
            }
        }
        return view('admin.' . $this->viewName . '.input', ['data' => $data]);
    }
}
