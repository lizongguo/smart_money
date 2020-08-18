<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\Permissions;

class PermissionsController extends BaseController
{
    protected $model = null;

    public function __construct(Permissions $model) {
        $this->model = $model;
        parent::__construct();
        view()->share('httpMethods', Permissions::$httpMethods);
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
            'slug' => 'required|regex:#^[\S]{4,}$#',
            'http_path' => 'required'
        ];
        $tips = [
            'name.required' => '权限名称为必填项',
            'slug.required' => '标示为必填项',
            'http_path.required' => 'HTTP请求路径不能为空',
            'slug.regex' => '权限标示格式输入错误'
        ];
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
}
