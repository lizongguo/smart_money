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
use App\Models\Booking;

class BookingController extends BaseController
{
    public function __construct(Request $request, Booking $model) {
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
        if (!empty($data['telphone'])) {
            $sh['telphone'] = ['conn' => 'lk', 'value' => $data['telphone']];
        }
        return $sh;
    }
    
    /**
     * 店铺设置
     */
    public function state(Request $request, $id) {
        
        $data = ['status' => 404, 'msg' => '操作失败。'];
        $id   = (int)$id;
        $item = $this->model->find($id);
        $state = $request->input('state', null);
        if (!!$item && !empty($state)) {
            $item->state = $state;
            $rs = $item->save();
            if ($rs !== false) {
                $data = ['status' => 200, 'msg' => '保存成功。'];
            }
        }
        return $data;
    }
}
