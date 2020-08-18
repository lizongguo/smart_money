<?php

namespace App\Models;
use Cache;
use DB;

class Setting extends BaseModel
{
    protected $table = 'setting';
    protected $primaryKey = 'id';
    
    public $isDeleted = false;
    public $timestamps = false;
    
    protected $shopSettingMap = [
        'postprandial_settlement' => ['alias' => '餐后结算', 'category' => '结算设置', 'default' => '1'],
        'queue_state' => ['alias' => '排号功能', 'category' => '排号设置', 'default' => '0'],
        'queue_postpone_number' => ['alias' => '过号顺延数', 'category' => '排号设置', 'default' => '0'],
        'queue_sms_number' => ['alias' => '短信通知位', 'category' => '排号设置', 'default' => '3'],
        'takeout_state' => ['alias' => '外卖功能', 'category' => '外卖设置', 'default' => '0'],
        'takeout_auto_receipt' => ['alias' => '自动接单', 'category' => '外卖设置', 'default' => '0'],
        'takeout_store_code' => ['alias' => '门店编号', 'category' => '外卖设置', 'default' => ''],
        'booking_state' => ['alias' => '预约功能', 'category' => '预约设置', 'default' => '0'],
    ];
    
    /**
     * 
     * @param type $loadCache
     * @return type
     */
    public function getSystemSetting($loadCache = false) {
        
        if($loadCache || !(Cache::has('service_setting'))) {
            $setting = $this->where('shop_id', 0)->pluck('value', 'name')->toArray();
            //保存缓存，设定60分失效
            Cache::put('service_setting', $setting, 60);
            return $setting;
        }
        return Cache::get('service_setting');
    }
    
    
    /**
     * 
     * @param type $shop_id
     * @param type $loadCache
     * @return type
     */
    public function getSystemAndShopSetting($shop_id, $loadCache = false) {
        $key = 'service_setting_s' . $shop_id;
        if($loadCache || !(Cache::has($key))) {
            $setting = $this->whereIn('shop_id', [0, $shop_id])->pluck('value', 'name')->toArray();
            //保存缓存，设定60分失效
            Cache::put($key, $setting, 60);
            return $setting;
        }
        return Cache::get($key);
    }
    
    /**
     * @param type $cate
     * @return type
     */
    public function getSettingByCategory($cate) {
        $setting = $this->where('category', (string)$cate)->pluck('value', 'name')->toArray();
        return $setting;
    }
    
    public function setShopDefaultOption($shop_id)
    {
        $insert = [];
        foreach ($this->shopSettingMap as $name => $val) {
            $insert[] = [
                'shop_id' => $shop_id,
                'name' => $name,
                'value' => $val['default'],
                'alias' => $val['alias'],
                'category' => $val['category'],
            ];
        }
        if (count($insert) > 0) {
            $this->insertBatch($insert);
        }
        return true;
    }
    
    public function saveShopOptions($post, $shop_id) {
        $data = $this->whereExtend(['shop_id' => $shop_id, 'order' => ['field' => 'category', 'sort' => 'ASC']])->pluck('value', 'name');
        \DB::beginTransaction();
        try {
            foreach ($post as $name => $val) {
                if (isset($data[$name]) && $val != $data[$name]) {
                    $this->whereExtend(['shop_id' => $shop_id, 'name' => $name])->update([
                        'value' => $val,
                        'alias' => $this->shopSettingMap[$name]['alias'],
                        'category' => $this->shopSettingMap[$name]['category'],
                    ]);
                } else if (!isset($data[$name])) {
                    $insert[] = [
                        'shop_id' => $shop_id,
                        'name' => $name,
                        'value' => $val,
                        'alias' => $this->shopSettingMap[$name]['alias'],
                        'category' => $this->shopSettingMap[$name]['category'],
                    ];
                }
            }
            if (count($insert) > 0) {
                $this->insertBatch($insert);
            }
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        //删除本地缓存
        $key = 'service_setting_s' . $shop_id;
        Cache::forget($key);
        return true;
    }
    
    /**
     * 保存系统配置
     * @param type $data
     * @param type $category
     * @return boolean
     */
    public function saveOptions($data, $category) {
        $history = $this->where('shop_id', 0)->where('category', $category)->pluck('value', 'name');
        \DB::beginTransaction();
        try {
            foreach ($data as $name => $val) {
                if (isset($history[$name]) && $val != $history[$name]) {
                    $this->whereExtend(['category' => $category, 'name' => $name])->update([
                        'value' => $val
                    ]);
                } else if (!isset($history[$name])) {
                    $insert[] = [
                        'shop_id' => 0,
                        'name' => $name,
                        'value' => $val,
                        'alias' => '',
                        'category' => $category,
                    ];
                }
            }
            if (count($insert) > 0) {
                $this->insertBatch($insert);
            }
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return true;
    }
    
    /**
     * 获取店铺指定分类的设置
     * @param type $shop_id
     * @param type $cate
     * @return type
     */
    public function getShopSettingByCategory($shop_id, $cate)
    {
        $setting = $this->where('category', (string)$cate)
            ->where('shop_id', $shop_id)
            ->pluck('value', 'name')
            ->toArray();
        return $setting;
    }
    
}