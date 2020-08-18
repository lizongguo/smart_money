<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Desks extends BaseModel
{
    protected $table = 'desks';
    protected $primaryKey = 'id';
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function shops() : BelongsTo
    {
        return $this->belongsTo(Shops::class, 'shop_id');
    }
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function type() : BelongsTo
    {
        return $this->belongsTo(QueueType::class, 'type_id');
    }
    
    /**
     * 扩展列表查询
     * @param type $sh 查询条件
     * @param type $all 是否全部查询
     * @param type $limit 每页数
     * @param type $field 查询字段
     * @return type
     */
    public function getList($sh=[], $all = false, $limit = 20, $field = null)
    {
        $rs = parent::getList($sh, $all, $limit, $field);
        foreach($rs as &$item){
            $item->shops = $item->shops;
            $item->type = $item->type;
        }
        return $rs;
    }
    
    public function getPrintingDeskQrcodeShops($format = false) {
        $sh = [
            'tb_shop.deleted' => 0,
            'tb_shop_desk.deleted' => 0,
            'tb_shop_desk.state' => 0,
        ];

        $obj = $this->select(DB::raw('distinct tb_shop.id'),
            'tb_shop.shop_sn',
            'tb_shop.user_id',
            'tb_shop.shop_name',
            'tb_shop.shop_type',
            'tb_shop_extend.app_id',
            'tb_shop_extend.wx_mch_id',
            'tb_shop_extend.wx_mch_key'
            )
            ->join('tb_shop', 'tb_shop.id', '=', 'tb_shop_desk.shop_id')
            ->join('tb_shop_extend', 'tb_shop.id', '=', 'tb_shop_extend.shop_id')
            ->orderBy('tb_shop.shop_type', 'desc')
            ->groupBy('tb_shop_desk.shop_id');
        
        $this->parseSh($obj, $sh);
        $data = $obj->get()->toArray();
        if($format) {
            $return = [];
            foreach($data as $item) {
                $return[$item['id']] = $item;
            }
            return $return;
        }
        return $data;
    }
    
}
