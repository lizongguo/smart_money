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
use App\Models\Setting;

class ShopsController extends BaseController
{
    public function __construct(Request $request, Shops $model) {
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
        if (!empty($data['shop_name'])) {
            $sh['shop_name'] = ['conn' => 'lk', 'value' => $data['shop_name']];
        }
        if (!empty($data['phone'])) {
            $sh['phone'] = ['conn' => 'lk', 'value' => $data['phone']];
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
            'shop_name' => 'required',
            'image' => 'required',
            'phone' => "required|regex:#^1[\d]{10}$#",
            'queue_show_path' => "required",
            
        ];
        if (empty($data['id'])) {
            $valid['shop_address'] = 'required';
        }
        $tips = [
            'shop_name.required' => '店铺名为必填项',
            'image.required' => '店铺图片不能为空',
            'email.unique' => '邮箱已经注册，请更换邮箱',
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式输入错误',
            'shop_address.required' => '店铺地址不能为空',
            'queue_show_path.required' => "大屏显示图或者视频不能为空"
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
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
            $result = $this->model->saveShop($data);
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
    
    /**
     * 销售统计
     * @param Request $request
     * @param type $shop_id
     */
    public function statistics (Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $shop_id = $request->get('shop_id', 0);
        //获取全部的店铺
        view()->share('shops', Shops::select('id', 'shop_name')->where('deleted', 0)->get());
        
        $statistics = new \App\Models\Statistics();
        
        $sh = [
            'month' => date('Ym', strtotime($month)),
        ];
        if ($shop_id > 0) {
            $sh['shop_id'] = $shop_id;
        }
        
        //获取统计数据
        $list = $statistics->whereExtend($sh)->orderBy('day', 'asc')->select('day', 'online_amount', 'offline_amount', 'total_amount')->get();
        $data = [
            "xAxis" => [],
            "yAxis" => [
                'online' => [],
                'offline' => [],
                'total' => [],
            ],
            'statistics' => [
                'online' => 0,
                'offline' => 0,
                'total' => 0,
            ]
        ];
        
        foreach ($list as $item) {
            $data['xAxis'][] = date('d日', strtotime($item->day));
            $data['yAxis']['online'][] = floatval($item->online_amount);
            $data['yAxis']['offline'][] = floatval($item->offline_amount);
            $data['yAxis']['total'][] = floatval($item->total_amount);
            $data['statistics']['online'] += floatval($item->online_amount);
            $data['statistics']['offline'] += floatval($item->offline_amount);
            $data['statistics']['total'] += floatval($item->total_amount);
        }
        return view('admin.' . $this->viewName . '.statistics', ['data' => $data, 'shop_id' => $shop_id, 'month' => $month]);
        
    }
    
    public function queueScreen(Request $request) {
        $shop_id  = $request->input('shop_id', 0);
        $shop = $this->model->getOne(intval($shop_id));
        if (!$shop) {
            //店铺不存在.
            return redirect()->back();
        }
        //获取全部的店铺
        view()->share('shop', $shop);
        
        return view('admin.' . $this->viewName . '.queueScreen');
    }
    
    public function getQueueItems(Request $request, $shop_id) {
        $back = [
            'status' => 0,
            'msg' => '',
            'data' => [],
        ];
        $queueTypeModel = new \App\Models\QueueType;
        $queueModel = new \App\Models\Queue();
        
        $day = date('Y-m-d');
        //查询店铺的队列分类号
        $list = $queueTypeModel->select('queue_type.id', 'queue_type.shop_id', 'name', 'prefix', 'desc', 'average_time',
            \DB::raw('count(queue.id) as wait_num'), \DB::raw('group_concat(queue.id order by queue.sort asc) as ids')
            )
            ->leftJoin('queue', function ($join) use($day) {
                $join->on('queue_type_id', '=','queue_type.id')
                    ->where('queue.day', '=', $day)
                    ->where('queue.state', '<', '2');
            })
            ->where('queue_type.shop_id', $shop_id)
            ->where('queue_type.state', 1)
            ->groupBy('queue_type.id')
            ->get();
            
        //没有排队直接返回空数组
        if (count($list) < 1) {
            return response()->json($back);
        }
        
        $map = [];
        $queue_ids = [0];
        foreach ($list as &$item) {
            $item->map = array_slice(explode(',', $item->ids), 0, 2);
            $queue_ids = array_merge($queue_ids, $item->map);
        }
        
        $queue = $queueModel->select('id', 'state', 'sort', 'alias', 'num', 'day')->whereIn('id', $queue_ids)->get();
        $queueMap = [];
        foreach ($queue as $q) {
            $queueMap[$q->id] = $q;
        }
        foreach ($list as &$item) {
            $item->first = isset($item->map[0]) && isset($queueMap[$item->map[0]]) ? $queueMap[$item->map[0]] : new \stdClass;
            $item->next = isset($item->map[1]) && isset($queueMap[$item->map[1]]) ? $queueMap[$item->map[1]] : new \stdClass;
        }
        $back['data'] = $list;
        return response()->json($back);
    }
    
}
