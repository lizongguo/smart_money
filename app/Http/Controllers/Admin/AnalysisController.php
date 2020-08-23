<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FundStock;
use Illuminate\Support\Facades\Auth;
use App\Models\Fund;
use App\Models\Stock;

use DB;

class AnalysisController extends BaseController
{
    public function __construct(Request $request, FundStock $model) {
        parent::__construct();
        $this->model = $model;
        view()->share('funds', Fund::select('id', 'name')->where('deleted', 0)->get());
        view()->share('stocks', Stock::select('id', 'name')->where('deleted', 0)->get());
    }
    
    function items(Request $request) {
        $limit = $request->input('limit', 10);
        $sh = $request->input('sh', []);
        $page = $request->input('page', 1);
        $offset = ($page-1)*$limit;
        $obj = $this->model->select('fund_stock.*', 'fund.name as fund_name', 'stock.name as stock_name');
        $obj->join('fund', 'fund.id', 'fund_stock.fund_id');
        $obj->join('stock', 'stock.id', 'fund_stock.stock_id');
        $obj->where('fund_stock.deleted', 0);
        if ($sh['fund_name']) {
            $obj->where('fund.name', 'like', '%'.$sh['fund_name'].'%');
        }
        if ($sh['stock_name']) {
            $obj->where('stock.name', 'like', '%'.$sh['stock_name'].'%');
        }
        $total = count($obj->get());
        $data = $obj->offset($offset)->limit($limit)->get()->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => $total,
            'data' => $data,
        ]);
    }

    function input(Request $request, $id = 0) {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->getOne($id)) {
            $data = $item;
            $data->id = $id;
        }
        if ($request->isMethod('post')) {
            $data = $request->input('data');
            if ($data['amount']>0&&$data['stock_num']>0) {
                $data['position_cost'] = round($data['amount']/$data['stock_num'], 4);
            }
            //check data
            $row = DB::table('fund_stock')->where('deleted', 0)->where('fund_id', $data['fund_id'])->where('stock_id', $data['stock_id'])->where('id', '!=', $data['id'])->first();
            if ($row) {
                return response()->json([
                    'status' => 500,
                    'msg' => '请勿重复添加',
                    'data' => $data
                ]);
            }
            $result = $this->model->saveItem($data);
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

    public function stock()
    {
        return view('admin.' . $this->viewName . '.stock');
    }

    function stockitems(Request $request) {
        $model = new \App\Models\StockStatis();
        $limit = $request->input('limit', 10);
        $sh = $request->input('sh', []);
        $page = $request->input('page', 1);
        $offset = ($page-1)*$limit;
        $obj = $model->select('stock_statis.*', 'stock.name as stock_name');
        $obj->join('stock', 'stock.id', 'stock_statis.stock_id');
        $obj->where('stock_statis.deleted', 0);
        if ($sh['stock_name']) {
            $obj->where('stock.name', 'like', '%'.$sh['stock_name'].'%');
        }
        $total = count($obj->get());
        $data = $obj->offset($offset)->limit($limit)->get()->toArray();
        foreach ($data as $k => &$v) {
            $detail = json_decode($v['detail'], true);
            $v['detail_str'] = implode("<br>", $detail);
        }
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => $total,
            'data' => $data,
        ]);
    }
}
