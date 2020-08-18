<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Roles;
use App\Models\Permissions;

class MenuController extends BaseController
{
    protected $model = null;

    public function __construct(Menu $model) {
        $this->model = $model;
        parent::__construct();
    }
    
    /*
     * 列表渲染
     */
    public function index(Request $request)
    {
        $menus = $this->model->getList([
            'order' => ['field' => 'order', 'sort' => 'asc']
        ], true, 0, ['id', 'parent_id', 'title', 'icon', 'uri', 'order', 'permission_id']);
        $data = [];
        foreach ($menus as $menu) {
            $data[$menu->parent_id][] = $menu;
        }
    	return view('admin.' . $this->viewName . '.index', compact('data'));
    }
    
    
    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'parent_id' => 'required',
            'title' => 'required',
            'icon' => 'required',
        ];
        $tips = [
            'title.required' => '标题为必填项',
            'email.unique' => '邮箱已经注册，请更换邮箱',
            'icon.required' => '图标不能为空',
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
        view()->share('roles', Roles::select('id', 'name', 'slug')->get());
        view()->share('permissions', Permissions::select('id', 'name', 'slug')->get());
        view()->share('parents', $this->model->selectOptions());
        
        return parent::input($request, $id);
    }
    
    /**
     * Save tree order from a input.
     *
     * @param string $serialize
     *
     * @return bool
     */
    public function saveOrder(Request $request)
    {
        $serialize = $request->input('order', "{}");
        $tree = json_decode($serialize, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return response()->json([
                'status' => 400,
                'msg' => "操作失败。"
            ]);
        }
        $this->model->saveOrder($tree);
        return response()->json([
            'status' => 200,
            'msg' => "顺序保存成功。"
        ]);
    }
}
