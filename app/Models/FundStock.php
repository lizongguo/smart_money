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
        $obj = $this->select('fund_stock.*', 'fund.name as fund_name', 'stock.name as stock_name','fund.ranking','stock.code');
        $obj->join('fund', 'fund.id', 'fund_stock.fund_id');
        $obj->join('stock', 'stock.id', 'fund_stock.stock_id');
        $datas = $obj->where('fund_stock.deleted', 0)->get()->toArray();
        $format = [];
        foreach ($datas as $k => $v) {
            $format[$v['stock_id']][] = $v;
        }
        $shurl = "http://hq.sinajs.cn/list=sh";
        $szurl = "http://hq.sinajs.cn/list=sz";
        foreach ($format as $k => $v) {
            $stock_code = '';
            $tempInsert = [];
            $detailArr = [];
            foreach ($v as $stk => $stv) {
                $ranking = $stv['ranking']==0 ? '暂无' : $stv['ranking'];
                $position_cost = $stv['position_cost']==0 ? '暂无' : $stv['position_cost'];
                $detailArr[] = $stv['fund_name'] . " : " . $ranking ." : ". $stv['position_cost'];
                $stock_code = $stv['code'];
            }
            $tempInsert = [
                'stock_id' => $k,
                'hold_num' => count($v),
                'detail' => json_encode($detailArr, true),
            ];
            $url = $shurl . $stock_code;
            $cotent = file_get_contents($url);
            $getcontent = iconv("gb2312", "utf-8",$cotent);
            $contentArr = explode(',', $getcontent);
            if (isset($contentArr[3])) {
                $tempInsert['curent_price'] = $contentArr[3];
            } else {
                $url = $szurl . $stock_code;
                $cotent = file_get_contents($url);
                $getcontent = iconv("gb2312", "utf-8",$cotent);
                $contentArr = explode(',', $getcontent);
                if (isset($contentArr[3])) {
                    $tempInsert['curent_price'] = $contentArr[3];
                }
            }
            DB::table('stock_statis')->insert($tempInsert);
        }
    }
}
