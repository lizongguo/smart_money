<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DB;

class FundStock extends BaseModel
{
    protected $table = 'fund_stock';
    protected $primaryKey = 'id';

    public function getOne($id)
    {
        $found = $this->select('*')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();
        if (!$found) {
            return false;
        }
        return $found;
    }

    public function dealStockData()
    {
        DB::statement('truncate table stock_statis');
        $obj = $this->select('fund_stock.*', 'fund.name as fund_name', 'stock.name as stock_name','fund.ranking');
        $obj->join('fund', 'fund.id', 'fund_stock.fund_id');
        $obj->join('stock', 'stock.id', 'fund_stock.stock_id');
        $datas = $obj->where('fund_stock.deleted', 0)->get()->toArray();
        $format = [];
        foreach ($datas as $k => $v) {
            $format[$v['stock_id']][] = $v;
        }
        foreach ($format as $k => $v) {
            $tempInsert = [];
            $detailArr = [];
            foreach ($v as $stk => $stv) {
                $ranking = $stv['ranking']==0 ? '暂无' : $stv['ranking'];
                $position_cost = $stv['position_cost']==0 ? '暂无' : $stv['position_cost'];
                $detailArr[] = $stv['fund_name'] . " : " . $ranking ." : ". $stv['position_cost'];
            }
            $tempInsert = [
                'stock_id' => $k,
                'hold_num' => count($v),
                'detail' => json_encode($detailArr, true),
            ];
            DB::table('stock_statis')->insert($tempInsert);
        }
    }
}
