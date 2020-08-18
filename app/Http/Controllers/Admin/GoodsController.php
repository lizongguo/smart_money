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
use App\Models\Goods;

class GoodsController extends BaseController
{
    public function __construct(Request $request, Goods $model) {
        parent::__construct();
        $this->model = $model;
        
        //获取全部的店铺
        view()->share('shops', Shops::select('id', 'shop_name')->where('deleted', 0)->get());
        //获取全部的店铺
        view()->share('categories', GoodsCategory::select('id', 'name')->where('deleted', 0)->orderBy('sort', 'asc')->get());
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
    
    
    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'goods_name' => 'required',
            'shop_ids' => 'required',
            'img' => 'required',
        ];
        
        if (!isset($data['is_multiple_spec']) || $data['is_multiple_spec'] < 1) {
            $valid['sell_price'] = 'regex:/^[\d]+(.[\d]+)?$/';
            unset($data['products']);
        }else{
            $valid['products.*.spec_str'] = 'required';
            $valid['products.*.sell_price'] = 'regex:/^[\d]+(.[\d]+)?$/';
        }
        
        $tips = [
            'goods_name.required' => '菜品名称为必填项',
            'img.required' => '展示图片不能为空',
            'sell_price.regex' => '售价输入不正确',
            'shop_ids.required' => '店铺为必选项',
            'products.*.spec_str.required' => '规格名不能为空',
            'products.*.sell_price.regex' => '规格售价格式有误',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
    
    /**
     * 商品 创建 编辑
     * @param Request $request
     * @return type
     */
    public function input(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->where('deleted', 0)->find($id)) {
            $item->shop_id = $item->shops()->pluck('shops.id')->toArray();
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
            //保存商品
            $result = $this->model->saveGoods($data);
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
    
    
    
    
    
    /**
     * 店铺设置
     */
    public function setting(Request $request, Setting $settingModel, $id) {
        
        $shop = $this->model->getOne(intval($id));
        if (!$shop) {
            //店铺不存在.
            return redirect()->back();
        }
        
        $data = $settingModel->whereExtend(['shop_id' => $shop->id, 'order' => ['field' => 'category', 'sort' => 'ASC']])->pluck('value', 'name');
        if ($request->isMethod('post')) {
            $post = $request->input('data');
            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorSettingItem') && $this->validatorSettingItem($post, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }
            $result = $settingModel->saveShopOptions($post, $shop->id);
            
            //save success
            if ($result) {
                return response()->json([
                    'status' => 200,
                    'msg' => '保存に成功しました。'
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存に失敗しました。',
                    'data' => $data
                ]);
            }
        }
        
        return view('admin.' . $this->viewName . '.setting', ['data' => $data]);
    }
}
