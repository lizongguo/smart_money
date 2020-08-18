<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\GoodsCategory;
use App\Models\Shops;
class GoodsCategoryController extends BaseController
{
    public function __construct(GoodsCategory $model) {
        $this->model = $model;
        parent::__construct();
        //获取全部的店铺
        view()->share('shops', Shops::select('id', 'shop_name')->where('deleted', 0)->get());
    }
    
    
    
}
