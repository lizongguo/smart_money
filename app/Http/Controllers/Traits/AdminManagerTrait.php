<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

/**
 * AdminManagerTrait
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2019-3-4 15:55:34
 * @copyright   Copyright(C) kbftech Inc.
 */
trait AdminManagerTrait {
    
    /*
     * 列表渲染
     */
    public function index(Request $request)
    {
    	return view('admin.' . $this->viewName . '.index');
    }
    
    /**
     * 列表数据json
     * @param Request $request
     * @return json_encode
     */
    public function items(Request $request, $arr = false) {
        $limit = $request->input('limit', 10);
        
        $sh = $request->input('sh', []);
        
        //特殊处理检索字段
        if (method_exists($this, 'parseSearch')) {
            $sh = $this->parseSearch($sh);
        }
        
        $list = $this->model->getList($sh, false, $limit > 0 ? $limit : 10);
        $rs = [
            'code' => 0,
            'msg' => '',
            'count' => $list->total(),
            'data' => $list->items(),
        ];

        if ($arr) {
            return $rs;
        } else {
            return response()->json($rs);
        }
    }
    
    /*
     * delete
     */
    public function deleted($id)
    {
        $data = ['status' => 500, 'msg' => '削除に失敗しました。'];
        $ids  = preg_split("/,/", $id, -1, 1);
        if (count($ids) > 0) {
            $rs = $this->model->deletedItem($ids);
            if ($rs !== false) {
                $data = ['status' => 200, 'msg' => '削除に成功しました。'];
            }
        }
        return $this->dataToJson($data);
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
    
    public function detail(Request $request, $id = 0)
    {
        $id = (int)$id;
        if ($id <= 0) {
            $data = ['status' => 404, 'msg' => '错误的操作。'];
            return $this->dataToJson($data);
        }
        $item = $this->model->find($id);

        return view('admin.' . $this->viewName . '.detail', ['data' => $item, 'currentMenu' => $this->currentMenu]);
    }
    
    protected function dataToJson($data, $httpd_code = '200')
    {
        return response()->json($data, $httpd_code);
    }
    
    /*
     * 修改状态
     */
    public function state(Request $request, $id)
    {
        $data = ['status' => 404, 'msg' => '保存失败。'];
        $id   = (int)$id;
        $item = $this->model->find($id);
        
        if (!!$item) {
            $state = 1;
            if($item['state'] == 1) {
                $state = 0;
            }
            $item->state = $state;
            $rs = $item->save();
            if ($rs !== false) {
                $data = ['status' => 200, 'msg' => '保存成功。'];
            }
        }
        return $data;
    }
}
