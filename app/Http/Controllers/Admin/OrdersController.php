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
use App\Models\Orders;
use App\Models\OrderTakeout;


class OrdersController extends BaseController
{
    public function __construct(Request $request, Orders $model) {
        parent::__construct();
        $this->model = $model;
        
        //获取全部的店铺
        view()->share('shops', Shops::select('id', 'shop_name')->where('deleted', 0)->get());
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
            $sh['created_at'] = ['conn' => 'between', 'value' => [$sh['start_date'] . ' 00:00:00', $sh['end_date'] . ' 23:59:59']];
            unset($sh['start_date']);
            unset($sh['end_date']);
        } elseif (!empty($sh['start_date'])) {
            $sh['created_at'] = ['conn' => '>=', 'value' => $sh['start_date'] . ' 00:00:00'];
            unset($sh['start_date']);
        } elseif (!empty($sh['end_date'])) {
            $sh['created_at'] = ['conn' => '>=', 'value' => $sh['end_date'] . ' 23:59:59'];
            unset($sh['end_date']);
        }
        if (!isset($sh['meal_no']) || $sh['meal_no'] < 1) {
            $sh['meal_no'] = ['conn' => '>', 'value' => 0];
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
    
    
    
    public function detail(Request $request, $id = 0)
    {
        $id = (int)$id;
        $item = $this->model->find($id);
        
        if(!!$item) {
            //外卖订单 查询外卖情报
            if ($item->is_takeout == 1) {
                $takeout = new OrderTakeout();
                $item->takeout = $takeout->getTakeoutByOrderId($item->id);
                $takeoutInfo = new \App\Models\TakeoutInfo();
                $item->takeoutInfo = $takeoutInfo->where('order_id', $item->id)->orderBy('id', 'asc')->get();
            }

            //优惠活动
            $activityModel = new \App\Models\OrderActivity();
            $item->activity = $activityModel->getOrderActivityByOrderId($item->id);
            
            //商品列表
            $goodsModel = new \App\Models\OrderGoods();
            $goods = $goodsModel->where('order_id', $item->id)->get();
            $item->goods = $goods;
        }else {
            view()->share('errorSession', "错误的访问请求，订单不存在。");
        }
        return view('admin.' . $this->viewName . '.detail', ['data' => $item]);
    }
    
}
