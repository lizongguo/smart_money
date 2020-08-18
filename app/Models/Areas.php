<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Log;

class Areas extends BaseModel
{
    public $timestamps = false;
    protected $table = 'areas';
    protected $primaryKey = 'id';
    
    
    public function getAreas() {
        $redisKey = config('rediskeys.areas');
        
        $areas = Redis::get($redisKey);
        if(!$areas) {
            $areas = $this->where('level','<=', '2')->select('id','parent_id', 'area_name', 'path')->get()->toArray();
            $data = [];
            foreach($areas as $area) {
                $data[$area['id']] = $area;
            }
            
            //缓存到redis
            Redis::set($redisKey, json_encode($data));
        } else {
            $data  = json_decode($areas, true);
        }
        
        return $data;
    }
}
