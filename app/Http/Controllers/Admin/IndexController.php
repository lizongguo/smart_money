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
use App\Models\Menu;
use App\Models\Roles;
use DB;

class IndexController extends BaseController
{
    public $currentMenu = 'index|admin.index';
    public function __construct() {
        parent::__construct();
    }
    
    public function index(Request $request, Menu $model)
    {
        $roles = new Roles();
        $menus = $roles->find($this->user->role)->menus()->get();
        //print_r($menus);exit;
        $data = [];
        foreach ($menus as $menu) {
            $data[$menu->parent_id][] = $menu;
        }
        
    	return view('admin.index.index', compact('data'));
    }
    
    public function console(Request $request)
    {
    	return view('admin.index.console');
    }
    
}
